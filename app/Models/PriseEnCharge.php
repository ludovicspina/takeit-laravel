<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Commentaire;

class PriseEnCharge extends Model
{
    protected $table = 'prises_en_charge';

    protected $fillable = [
        'client_id', 'type', 'marque', 'modele', 'numero_serie',
        'mot_de_passe', 'accessoires', 'etat', 'demarrage_pc',
        'demarrage_os', 'dommages', 'description', 'remarques',
        'statut'
    ];

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class, 'prise_en_charge_id');
    }
}
