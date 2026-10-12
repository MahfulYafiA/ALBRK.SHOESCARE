<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ViewModels\Shared\DashboardViewModel;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        private DashboardViewModel $dashboardViewModel
    ) {}

    /**
     * Show admin dashboard
     */
    public function dashboard(): View
    {
        return view('admin.dasbor', $this->dashboardViewModel->getDashboardData());
    }
}
