<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LaporanIndexRequest;
use App\ViewModels\Shared\LaporanViewModel;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function __construct(private LaporanViewModel $laporanViewModel) {}

    public function index(LaporanIndexRequest $request): View
    {
        $filters = array_merge($request->validated(), ['cetak' => $request->boolean('cetak')]);

        return view('admin.laporan', $this->laporanViewModel->getReportData($filters));
    }
}
