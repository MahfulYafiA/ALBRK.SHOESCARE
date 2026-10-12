<?php

namespace App\Http\Controllers;

use App\ViewModels\LandingViewModel;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __construct(private LandingViewModel $landingViewModel) {}

    public function index(): View
    {
        return view('beranda.beranda', $this->landingViewModel->getPageData());
    }
}
