<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'montant',
        'status',
        'users_id',
        'type_prestation_id',
        'ayant_droits_id',
    ];

    public function membre()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    function type_prestation()
    {
        return $this->belongsTo(TypePrestation::class, 'type_prestation_id', 'id');
    }
    function ayant_droit()
    {
        return $this->belongsTo(AyantDroit::class, 'ayant_droits_id', 'id');
    }
}
