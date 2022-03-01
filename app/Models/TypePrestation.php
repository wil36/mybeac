<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePrestation extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'montant',
        'delete_ayant_droit',
    ];

    function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}