<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\PriseEnCharge;

class Commentaire extends Model
{
    protected $fillable = ['prise_en_charge_id', 'contenu'];

    public function prise(): BelongsTo
    {
        return $this->belongsTo(PriseEnCharge::class, 'prise_en_charge_id');
    }
}
