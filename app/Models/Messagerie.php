<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messagerie extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'link_file',
        'date',
        'etat',
        'users_id',
        'expediteur',
    ];

    public function membre()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}