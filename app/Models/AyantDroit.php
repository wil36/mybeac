<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AyantDroit extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'prenom',
        'statut',
        'cni',
        'acte_naissance',
        'certificat_vie',
    ];

    function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}