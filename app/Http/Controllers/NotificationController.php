<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function readAll()
    {
        Notification::where('etat', 'Non lue')->update(['etat' => 'Lue']);
        return back();
    }
}