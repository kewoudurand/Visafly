<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        return view('index');
    }

    public function dashboard()
    {
        $user    = Auth::user();
        $service = new DashboardService();

        $widgets = $service->widgetsFor($user);
        $stats   = $service->statsFor($user);

        return view('dashboard', compact('widgets', 'stats', 'user'));
    }
}