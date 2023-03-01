<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emprunt extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'type',
        'objet',
        'link_lettre_souscription',
        'link_avis_imposition',
        'link_bulletin_salaire',
        'link_devis_travaux',
        'link_proposition_vente',
        'link_contrat_travail',
        'link_autres',
        'date_de_validation',
        'montant',
        'montant_commission',
        'date',
        'date_de_fin',
        'etat',
        'users_id',
    ];

    public function membre()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}