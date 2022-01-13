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
        return $this->belongsTo(User::class);
    }

    function type_prestation()
    {
        return $this->belongsTo(Category::class);
    }
    function ayant_droit()
    {
        return $this->belongsTo(Category::class);
    }
}