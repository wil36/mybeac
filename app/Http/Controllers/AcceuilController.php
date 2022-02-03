<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceuilController extends Controller
{
    public function index()
    {
        return view('pages.acceuil');
    }

    public function changeTheme()
    {
        try {
            $user = User::find(Auth::user()->id);
            $user->theme = Auth::user()->theme == 0 ? 1 : 0;
            $user->save();
            return true;
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
}