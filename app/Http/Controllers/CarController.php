<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CarController extends Controller
{
    public function show(Car $car)
    {
        return view('cars-detail', compact('car'));
    }

    public function downloadBarcode(Car $car)
    {
        $url = route('cars.show', $car->id);

        $qr = QrCode::format('svg')
            ->size(300)
            ->generate($url);

        return response($qr)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="car_qr_'.$car->id.'.svg"');
    }
}
