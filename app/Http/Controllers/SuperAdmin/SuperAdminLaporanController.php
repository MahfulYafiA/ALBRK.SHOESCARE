<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LaporanIndexRequest;
use App\ViewModels\Shared\LaporanViewModel;
use Illuminate\View\View;

class SuperAdminLaporanController extends Controller
{
    public function __construct(
        private LaporanViewModel $laporanViewModel
    ) {}

    /**
     * Show laporan page
     */
    public function index(LaporanIndexRequest $request): View
    {
        $filters = array_merge($request->validated(), ['cetak' => $request->boolean('cetak')]);

        return view('admin.laporan', $this->laporanViewModel->getReportData($filters));
    }
}
