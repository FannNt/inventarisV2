<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function show(Car $car)
    {
        return view('cars-detail', compact('car'));
    }
}
