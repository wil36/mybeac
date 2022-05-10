<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dons extends Model
{
    use HasFactory;
    //TODO: gérer les type des montants
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'tel',
        'date',
        'montant',
        'sexe',
        'type',
        'users_id',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }
}