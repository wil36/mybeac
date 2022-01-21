<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotisationController extends Controller
{

    public function cotisation()
    {
        $montant_global = Cotisation::sum('montant');
        $last_numero_seance = Cotisation::select('numero_seance')->latest()->first();
        // dd($last_numero_seance->numero_seance);
        return view('pages.cotisation', ['montant_global' => number_format($montant_global, 0, ',', ' '), 'numero_seance' => number_format($last_numero_seance->numero_seance + 1, 0, ',', ' ')]);
    }

    public function getUserCotisation(Request $request)
    {
        // $data = User::with('category')->latest()->get();
        $data = DB::select(DB::raw('select us.id, us.nom, us.prenom, us.created_at, us.profile_photo_path, us.matricule, us.agence, us.tel, us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca where ca.id=us.categories_id'));
        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("id", function ($data) {
                return $data->id;
            })
            ->addColumn("select", function ($data) {
                return '<div class="custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" onclick="setCheckBox();" name="registrations[]" value="' . $data->id . '" id="customCheck' . $data->id . '">
     <label class="custom-control-label" for="customCheck' . $data->id . '"></label>
</div>';
            })
            ->addColumn("updated_at", function ($data) {
                return $data->created_at;
            })
            ->editColumn("matricule", function ($data) {
                return "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                CO
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>$data->matricule</span>
                </div>
            </div>";
            })
            ->addColumn("nom", function ($data) {
                return $data->nom;
            })
            ->addColumn("montant", function ($data) {
                return $data->montant;
            })
            ->addColumn("prenom", function ($data) {
                return $data->prenom;
            })
            ->addColumn("nationalité", function ($data) {
                return $data->nationalité;
            })
            ->addColumn("agence", function ($data) {
                return $data->agence;
            })
            ->addColumn("tel", function ($data) {
                return $data->tel;
            })
            ->addColumn("category", function ($data) {
                return $data->libelle;
            })
            ->editColumn("status", function ($data) {
                if ($data->status) {
                    return ' <td class="nk-tb-col tb-col-md">
                    <span class="badge badge-outline-success">Actif</span>
                </td>';
                } else {
                    return ' <td class="nk-tb-col tb-col-md">
                <span class="badge badge-outline-danger">Inactif</span>
            </td>';
                }
            })
            ->addColumn('Actions', function ($data) {
                return '<ul class="nk-tb-actions gx-1">
               
              
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"></a>
                        <div class="dropdown-menu dropdown-menu-right">
                           
                        </div>
                    </div>
                </li>
            </ul>';
            })->setRowClass("nk-tb-item")
            ->rawColumns(['matricule', 'select', 'status', 'Actions',])
            ->make(true);
    }

    public function savecotisation(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'liste' => ['required'],
            'date' => ['required', 'date', 'date_format:Y-m-d'],
            'num_seance' => ['required', 'integer'],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        foreach ($request->liste as $item) {
            $cotisation = new Cotisation();
            $cotisation->date = $request->date;
            $cotisation->montant = 50000;
            $cotisation->numero_seance = $request->num_seance;
            $cotisation->users_id = $item;
            $cotisation->save();
        }
        return response()->json(["success" => "Enregistrement éffectuer !"]);
    }
}