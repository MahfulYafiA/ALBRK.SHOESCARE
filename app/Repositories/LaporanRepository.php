<?php

namespace App\Repositories;

use App\Models\DetailReservasi;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use App\Models\User;
use App\Repositories\Contracts\LaporanRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class LaporanRepository implements LaporanRepositoryInterface
{
    private const SUCCESS_PAYMENT_STATUSES = ['lunas', 'settlement', 'capture', 'berhasil'];

    public function getReportData(array $filters): array
    {
        $period = $filters['periode'] ?? 'bulan_ini';
        $today = now();
        [$start, $end] = match ($period) {
            'hari_ini' => [$today->copy()->startOfDay(), $today->copy()->endOfDay()],
            'minggu_ini' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()],
            'tahun_ini' => [$today->copy()->startOfYear(), $today->copy()->endOfYear()],
            'custom' => [Carbon::parse($filters['tanggal_mulai'])->startOfDay(), Carbon::parse($filters['tanggal_akhir'])->endOfDay()],
            default => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
        };
        $startDate = $start->toDateString();
        $endDate = $end->toDateString();
        $q = trim($filters['q'] ?? '');

        $reservations = fn () => Reservasi::query()->whereBetween('tanggal_reservasi', [$startDate, $endDate]);
        $payments = fn () => Pembayaran::query()->whereBetween(DB::raw('DATE(COALESCE(tanggal_bayar, created_at))'), [$startDate, $endDate]);

        $reservationCounts = [
            'total' => (clone $reservations())->count(),
            'menunggu' => (clone $reservations())->where('status', 'menunggu')->count(),
            'dikonfirmasi' => (clone $reservations())->whereIn('status', ['di_terima', 'diterima'])->count(),
            'selesai' => (clone $reservations())->whereIn('status', ['selesai', 'diambil'])->count(),
            'dibatalkan' => (clone $reservations())->where('status', 'dibatalkan')->count(),
        ];
        $paymentCounts = [
            'total' => (clone $payments())->count(),
            'berhasil' => (clone $payments())->whereIn(DB::raw('LOWER(status)'), self::SUCCESS_PAYMENT_STATUSES)->count(),
            'menunggu' => (clone $payments())->whereIn(DB::raw('LOWER(status)'), ['menunggu', 'pending'])->count(),
            'ditolak' => (clone $payments())->whereIn(DB::raw('LOWER(status)'), ['ditolak', 'gagal', 'deny', 'cancel'])->count(),
            'nominal_berhasil' => (int) (clone $payments())->whereIn(DB::raw('LOWER(status)'), self::SUCCESS_PAYMENT_STATUSES)->sum('jumlah'),
        ];
        $customerBase = User::query()->whereIn('role', ['pelanggan', 'customer']);
        $customerCounts = [
            'total' => (clone $customerBase)->whereBetween('created_at', [$start, $end])->count(),
            'aktif' => (clone $customerBase)->whereBetween('created_at', [$start, $end])->where('status', 'Aktif')->count(),
            'baru' => (clone $customerBase)->whereBetween('created_at', [$start, $end])->count(),
            'transaksi' => (clone $customerBase)->whereBetween('created_at', [$start, $end])->whereHas('reservasis', fn (Builder $query) => $query->whereBetween('tanggal_reservasi', [$startDate, $endDate]))->count(),
        ];

        $reservationQuery = $reservations()->with(['user', 'detailReservasis.layanan']);
        if ($q !== '') {
            $reservationQuery->where(fn (Builder $query) => $this->searchReservations($query, $q));
        }
        $reservationRows = $reservationQuery->orderByDesc('tanggal_reservasi')->orderByDesc('id_reservasi')->paginate(10, ['*'], 'reservasi_page')->withQueryString();

        $paymentQuery = $payments()->with(['reservasi.user']);
        if ($q !== '') {
            $paymentQuery->where(fn (Builder $query) => $query->where('id_pembayaran', 'like', "%{$q}%")
                ->orWhereHas('reservasi', fn (Builder $reservation) => $this->searchReservations($reservation, $q)));
        }
        $paymentRows = $paymentQuery->orderByDesc('tanggal_bayar')->orderByDesc('id_pembayaran')->paginate(10, ['*'], 'pembayaran_page')->withQueryString();

        $customerQuery = (clone $customerBase)->whereBetween('created_at', [$start, $end])
            ->withCount(['reservasis as jumlah_reservasi' => fn (Builder $query) => $query->whereBetween('tanggal_reservasi', [$startDate, $endDate])])
            ->withSum(['reservasis as total_transaksi' => fn (Builder $query) => $query->whereBetween('tanggal_reservasi', [$startDate, $endDate])], 'total_harga');
        if ($q !== '') {
            $customerQuery->where(fn (Builder $query) => $query->where('nama', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")->orWhere('no_telp', 'like', "%{$q}%"));
        }
        $customerRows = $customerQuery->orderByDesc('created_at')->paginate(10, ['*'], 'pelanggan_page')->withQueryString();

        $serviceQuery = DetailReservasi::query()
            ->join('ms_layanan', 'ms_layanan.id_layanan', '=', 'tr_detail_reservasi.id_layanan')
            ->join('tr_reservasi', 'tr_reservasi.id_reservasi', '=', 'tr_detail_reservasi.id_reservasi')
            ->whereBetween('tr_reservasi.tanggal_reservasi', [$startDate, $endDate]);
        if ($q !== '') {
            $serviceQuery->where('ms_layanan.nama_layanan', 'like', "%{$q}%");
        }
        $serviceRows = $serviceQuery->select('ms_layanan.id_layanan', 'ms_layanan.nama_layanan')
            ->selectRaw('COUNT(DISTINCT tr_reservasi.id_reservasi) as jumlah_reservasi')
            ->selectRaw('COALESCE(SUM(tr_detail_reservasi.jumlah), 0) as jumlah_sepatu')
            ->selectRaw('COALESCE(SUM(CASE WHEN LOWER(COALESCE(tr_reservasi.status_bayar, \'\')) = \'lunas\' THEN COALESCE(tr_detail_reservasi.sub_total, tr_detail_reservasi.harga * tr_detail_reservasi.jumlah) ELSE 0 END), 0) as total_pendapatan')
            ->groupBy('ms_layanan.id_layanan', 'ms_layanan.nama_layanan')->orderByDesc('jumlah_reservasi')->paginate(10, ['*'], 'layanan_page')->withQueryString();

        $chart = $this->buildChart($startDate, $endDate, $period);
        $totalIncome = $paymentCounts['nominal_berhasil'];
        $incomePeriods = [];
        foreach ([
            'Pendapatan Hari Ini' => [now()->copy()->startOfDay(), now()->copy()->endOfDay()],
            'Pendapatan Minggu Ini' => [now()->copy()->startOfWeek(), now()->copy()->endOfWeek()],
            'Pendapatan Bulan Ini' => [now()->copy()->startOfMonth(), now()->copy()->endOfMonth()],
            'Pendapatan Tahun Ini' => [now()->copy()->startOfYear(), now()->copy()->endOfYear()],
        ] as $label => [$periodStart, $periodEnd]) {
            $from = $periodStart->greaterThan($start) ? $periodStart : $start;
            $to = $periodEnd->lessThan($end) ? $periodEnd : $end;
            $amount = $from->greaterThan($to) ? 0 : Pembayaran::query()
                ->whereBetween(DB::raw('DATE(COALESCE(tanggal_bayar, created_at))'), [$from->toDateString(), $to->toDateString()])
                ->whereIn(DB::raw('LOWER(status)'), self::SUCCESS_PAYMENT_STATUSES)->sum('jumlah');
            $incomePeriods[] = ['label' => $label, 'amount' => (int) $amount];
        }

        return compact('period', 'startDate', 'endDate', 'q', 'reservationCounts', 'paymentCounts', 'customerCounts', 'reservationRows', 'paymentRows', 'customerRows', 'serviceRows', 'chart', 'totalIncome', 'incomePeriods');
    }

    private function searchReservations(Builder $query, string $term): void
    {
        $formatted = preg_match('/^#?RSV[- ]?(\d{8})[- ]?(\d+)$/i', $term, $matches) === 1
            && checkdate((int) substr($matches[1], 4, 2), (int) substr($matches[1], 6, 2), (int) substr($matches[1], 0, 4))
            ? $matches
            : null;

        $query->where('id_reservasi', 'like', "%{$term}%")
            ->orWhere('nama_pelanggan', 'like', "%{$term}%")
            ->orWhere('no_hp', 'like', "%{$term}%")
            ->orWhereHas('user', fn (Builder $user) => $user->where('nama', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));

        if ($formatted) {
            $query->orWhere(fn (Builder $number) => $number
                ->whereDate('tanggal_reservasi', substr($formatted[1], 0, 4).'-'.substr($formatted[1], 4, 2).'-'.substr($formatted[1], 6, 2))
                ->where('id_reservasi', (int) $formatted[2]));
        }
    }

    private function buildChart(string $start, string $end, string $period): array
    {
        $monthly = $period === 'tahun_ini';
        $driver = DB::connection()->getDriverName();
        $reservationBucket = $monthly
            ? ($driver === 'sqlite' ? "strftime('%Y-%m', tanggal_reservasi)" : "DATE_FORMAT(tanggal_reservasi, '%Y-%m')")
            : 'DATE(tanggal_reservasi)';
        $reservationRows = Reservasi::query()->whereBetween('tanggal_reservasi', [$start, $end])
            ->selectRaw("{$reservationBucket} as bucket, COUNT(*) as jumlah")
            ->groupBy('bucket')->get()->keyBy('bucket');
        $paymentBucket = $monthly
            ? ($driver === 'sqlite' ? "strftime('%Y-%m', COALESCE(tanggal_bayar, created_at))" : "DATE_FORMAT(COALESCE(tanggal_bayar, created_at), '%Y-%m')")
            : 'DATE(COALESCE(tanggal_bayar, created_at))';
        $paymentRows = Pembayaran::query()->whereBetween(DB::raw('DATE(COALESCE(tanggal_bayar, created_at))'), [$start, $end])
            ->whereIn(DB::raw('LOWER(status)'), self::SUCCESS_PAYMENT_STATUSES)
            ->selectRaw("{$paymentBucket} as bucket, SUM(jumlah) as pendapatan")
            ->groupBy('bucket')->get()->keyBy('bucket');

        $labels = [];
        $reservations = [];
        $income = [];
        $interval = $monthly ? CarbonPeriod::create(Carbon::parse($start)->startOfMonth(), '1 month', Carbon::parse($end)->startOfMonth()) : CarbonPeriod::create($start, $end);
        foreach ($interval as $date) {
            $key = $monthly ? $date->format('Y-m') : $date->format('Y-m-d');
            $labels[] = $monthly ? $date->translatedFormat('M') : $date->translatedFormat('d M');
            $reservations[] = (int) ($reservationRows[$key]->jumlah ?? 0);
            $income[] = (int) ($paymentRows[$key]->pendapatan ?? 0);
        }

        $services = DB::table('tr_detail_reservasi')->join('ms_layanan', 'ms_layanan.id_layanan', '=', 'tr_detail_reservasi.id_layanan')
            ->join('tr_reservasi', 'tr_reservasi.id_reservasi', '=', 'tr_detail_reservasi.id_reservasi')
            ->whereBetween('tr_reservasi.tanggal_reservasi', [$start, $end])->select('ms_layanan.nama_layanan')
            ->selectRaw('COUNT(DISTINCT tr_reservasi.id_reservasi) as jumlah')->groupBy('ms_layanan.id_layanan', 'ms_layanan.nama_layanan')
            ->orderByDesc('jumlah')->limit(5)->get();

        return ['labels' => $labels, 'reservations' => $reservations, 'income' => $income, 'services' => $services->pluck('nama_layanan')->all(), 'serviceCounts' => $services->pluck('jumlah')->map(fn ($n) => (int) $n)->all()];
    }
}
