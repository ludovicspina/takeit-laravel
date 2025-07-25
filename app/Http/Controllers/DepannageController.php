<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DepannageController extends Controller
{
    public function index()
    {
        $depannages = DB::table('lara_prises_en_charge')
            ->leftJoin('llx_societe', 'lara_prises_en_charge.client_id', '=', 'llx_societe.rowid')
            ->leftJoin('lara_materiels', 'lara_prises_en_charge.materiel_id', '=', 'lara_materiels.id')
            ->select(
                'lara_prises_en_charge.*',
                'llx_societe.nom as client_nom',
                'lara_materiels.marque as marque',
                'lara_materiels.modele as modele',
                'lara_materiels.numero_serie as numero_serie'
            )
            ->orderByDesc('lara_prises_en_charge.created_at')
            ->get();

        return Inertia::render('Depannages', [
            'depannages' => $depannages
        ]);
    }

    public function show($id)
    {
        $depannage = DB::table('lara_prises_en_charge')
            ->leftJoin('llx_societe', 'lara_prises_en_charge.client_id', '=', 'llx_societe.rowid')
            ->leftJoin('lara_materiels', 'lara_prises_en_charge.materiel_id', '=', 'lara_materiels.id')
            ->select(
                'lara_prises_en_charge.*',
                'llx_societe.nom as client_nom',
                'llx_societe.address as client_adresse',
                'llx_societe.zip as client_zip',
                'llx_societe.town as client_ville',
                'llx_societe.phone as client_tel',
                'llx_societe.email as client_email',
                'lara_materiels.marque as marque',
                'lara_materiels.modele as modele',
                'lara_materiels.numero_serie as numero_serie',
                'lara_materiels.accessoires as accessoires',
                'lara_materiels.etat as etat'
            )
            ->where('lara_prises_en_charge.id', $id)
            ->first();

        return Inertia::render('Show', [
            'depannage' => $depannage
        ]);
    }
}
