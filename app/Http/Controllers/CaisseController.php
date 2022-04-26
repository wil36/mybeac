<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\Category;
use App\Models\Cotisation;
use App\Models\Prestation;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaisseController extends Controller
{
    public function index()
    {
        try {
            $caisse = Caisse::first();
            if (!isset($caisse)) {
                $caisse = new Caisse();
                $caisse->principal = 0;
                $caisse->quantine = 0;
                $caisse->emprunt = 0;
                $caisse->prestation = 0;
                $caisse->save();
            }
            return view('pages.caisse', [
                'principal' => number_format(abs($caisse->principal), 0, ',', ' '),
                'quantine' =>  number_format(abs($caisse->quantine), 0, ',', ' '),
                'emprunt' => number_format(abs($caisse->emprunt), 0, ',', ' '),
                // 'prestation' => number_format(abs($caisse->prestation), 0, ',', ' '),
            ]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
}