<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Assurez-vous que la vue "dashboard.connected" existe
        return view('dashboard.connected'); // Vue à définir
    }
}
