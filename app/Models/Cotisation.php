<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotisation extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'montant',
        'statut',
        'users_id',
        'numero_seance',
    ];

    public function membre()
    {
        return $this->belongsTo(User::class);
    }
}