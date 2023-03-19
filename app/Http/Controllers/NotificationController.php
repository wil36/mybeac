<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function readAll()
    {
        if (Auth::user()->role == 'admin') {
            Notification::where(function ($query) {
                $query->where('etat', 'Non lue');
                $query->where('destinataire', '=', null);
            })->orWhere(function ($query) {
                $query->where('etat', 'Non lue');
                $query->where('destinataire', '=', Auth::user()->id);
            })->update(['etat' => 'Lue']);
        } else {
            Notification::where(function ($query) {
                $query->where('etat', 'Non lue');
                $query->where('destinataire', '=', Auth::user()->id);
            })->update(['etat' => 'Lue']);
        }
        return back();
    }
}