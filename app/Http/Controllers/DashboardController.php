<?php

namespace App\Http\Controllers;

use App\Models\CarOwner;
use App\Models\CarRepair;
use App\Models\Mechanic;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['carowner'] = CarOwner::count();
        $data['mechanic'] = Mechanic::count();
        $data['service'] = Service::count();
        $data['repair'] = CarRepair::count();
        // dd($data);
        return view('dashboard', $data);
    }
}
