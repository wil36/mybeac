<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'libelle',
        'montant',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}