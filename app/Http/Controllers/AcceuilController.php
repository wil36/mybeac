<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Cotisation;
use App\Models\Dons;
use App\Models\Prestation;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AcceuilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $id = Auth::user()->id;
        $membre = User::select('id', 'nom', 'prenom', 'matricule', 'email', 'date_hadésion', 'nationalité', 'agence', 'sexe', 'categories_id', 'date_naissance', 'profile_photo_path')->where('id', $id)->first();
        $totalcotisation = Cotisation::where('users_id', '=', $id)->sum('montant');
        $totalprestation = Prestation::where('users_id', '=', $id)->sum('montant');
        $totalcotisationglobal = Cotisation::sum('montant');
        $totalprestationglobal = Prestation::sum('montant');
        $totalDons = Dons::sum('montant');
        $nbmembre = User::count();
        $nbprestation = Prestation::count();
        $poidMembre = $totalcotisation - $totalprestation;
        $cat = Category::find($membre->categories_id);
        $listeCotisation = DB::select(DB::raw(
            "SELECT SUM(co.montant) AS nombre, MONTH(co.date) as dat FROM cotisations co GROUP BY YEAR(co.date), MONTH(co.date) ORDER BY co.date desc"
        ));
        $listePrestation = DB::select(DB::raw("SELECT SUM(pr.montant) AS nombre, MONTH(pr.date) as dat FROM prestations pr GROUP BY YEAR(pr.date), MONTH(pr.date) ORDER BY pr.date desc"));
        return view('pages.acceuil', [
            'nbprestation' => number_format(abs($nbprestation), 0, ',', ' '),
            'nbmembre' =>  number_format(abs($nbmembre), 0, ',', ' '),
            'totalcotisationglobal' => number_format(abs($totalcotisationglobal), 0, ',', ' '),
            'totalprestationglobal' => number_format(abs($totalprestationglobal), 0, ',', ' '),
            'membre' => $membre,
            'category' => $cat,
            'listeCotisation' => $listeCotisation,
            'listePrestation' => $listePrestation,
            'totalDons' => number_format(abs($totalDons), 0, ',', ' '),
            'poidMembre' => $poidMembre,
            'poidMembre2' => number_format(abs($poidMembre), 0, ',', ' '),
            'totalCotisation' => number_format($totalcotisation, 0, ',', ' '),
            'totalPrestation' => number_format($totalprestation, 0, ',', ' '),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function infomembre(Request $request)
    {
        try {
            if ($request->id != null) {
                $membre = User::select('id', 'nom', 'prenom', 'matricule', 'email', 'date_hadésion', 'nationalité', 'agence', 'sexe', 'categories_id', 'date_naissance', 'profile_photo_path')->where('id', $request->id)->first();
                $totalcotisation = Cotisation::where('users_id', '=', $request->id)->sum('montant');
                $totalprestation = Prestation::where('users_id', '=', $request->id)->sum('montant');
                $poidMembre = $totalcotisation - $totalprestation;
                $cat = Category::find($membre->categories_id);
                return view('pages.informationmembre', ['membre' => $membre, 'category' => $cat, 'poidMembre' => $poidMembre, 'poidMembre2' => number_format(abs($poidMembre), 0, ',', ' '), 'totalCotisation' => number_format($totalcotisation, 0, ',', ' '), 'totalPrestation' => number_format($totalprestation, 0, ',', ' ')]);
            } else {
                abort(404);
            }
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
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