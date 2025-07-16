<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    public function store(Request $request, $priseId)
    {
        $request->validate([
            'contenu' => 'required|string',
        ]);

        $prise = PriseEnCharge::findOrFail($priseId);

        $commentaire = new Commentaire([
            'contenu' => $request->contenu,
        ]);

        $prise->commentaires()->save($commentaire);

        return response()->json($commentaire, 201);
    }

    public function index($priseId)
    {
        $prise = PriseEnCharge::with('commentaires')->findOrFail($priseId);
        return response()->json($prise->commentaires);
    }
}
