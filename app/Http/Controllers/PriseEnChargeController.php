<?php

namespace App\Http\Controllers;

use App\Models\PriseEnCharge;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PriseEnChargeController extends Controller
{
    public function depannages()
    {
        $depannages = PriseEnCharge::with('client')
            ->where('status', 'en cours')
            ->latest()
            ->get();

        return Inertia::render('Depannages', [
            'depannages' => $depannages
        ]);
    }
}
