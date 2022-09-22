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
        'date_de_validation',
        'montant',
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