<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function showDashboardPage() {
        return view('dashboard.index');
    }
}