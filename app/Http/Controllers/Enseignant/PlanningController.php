<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    public function index()
    {
        return view('enseignant.planning');
    }

    public function cours()
    {
        return view('enseignant.dashboard');
    }
}
