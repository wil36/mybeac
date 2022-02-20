<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotisationController extends Controller
{

    public function cotisation()
    {
        try {
            $montant_global = Cotisation::sum('montant');
            // $last_numero_seance = Cotisation::select('numero_seance')->where('date', '!=', Carbon::now()->format('Y-m-d'))->latest()->first() ?? 0;
            $last_numero_seance = DB::select(DB::raw('select max(numero_seance) as num from cotisations co where Month(co.date) < ' . Carbon::now()->format('m') . ' and Year(co.date) <= ' . Carbon::now()->format('Y')));
            // dd($last_numero_seance[0]->num);
            return view('pages.cotisation', ['montant_global' => number_format($montant_global ?? 0, 0, ',', ' '), 'numero_seance' => number_format(isset($last_numero_seance[0]->num) ? $last_numero_seance[0]->num + 1 : 0 + 1, 0, ',', ' ')]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function getUserCotisation(Request $request)
    {
        try {
            $month = Carbon::parse($request->date)->format('m');
            $year = Carbon::parse($request->date)->format('Y');
            // $data = User::with('category')->latest()->get();
            $data = DB::select(DB::raw('select us.id, us.nom, us.prenom, us.created_at, us.profile_photo_path, us.matricule, us.agence, us.tel, us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca where ca.id=us.categories_id and us.id not in (select co.users_id from cotisations co where Month(co.date)=' . $month . ' and Year(co.date)=' . $year . ')'));
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
                    return number_format($data->montant, 0, ',', ' ');
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
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function getcotisationListForUser(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select id, created_at, montant, DATE(date) as dates, numero_seance from cotisations where users_id=' . $request->id));
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->editColumn("date", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>COT</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->dates . "</span>
                </div>
            </div>";
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })
                ->addColumn("numero_seance", function ($data) {
                    return $data->numero_seance;
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                    <a href="#" class="btn btn-trigger btn-icon delete-data-cot" data_id="' . $data->id . '" data-toggle="tooltip" data-placement="top" title="Supprimer">
                       <em class="icon ni ni-trash"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li ><a href="" class="delete-data-cot" data_id="' . $data->id . '"  ><em class="icon ni ni-trash"></em><span>Supprimer</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['libelle', 'Actions', 'date', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function savecotisation(Request $request)
    {
        try {
            $validator = FacadesValidator::make($request->all(), [
                'date' => ['required', 'date', 'date_format:Y-m-d'],
                'num_seance' => ['required', 'integer'],
            ]);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            if (!isset($request['liste'])) {
                return response()
                    ->json(["errors" => ["Veuillez sélectionner un membre."]]);
            }
            foreach ($request->liste as $item) {
                $cotisation = new Cotisation();
                $cotisation->date = $request->date;
                if (date('m', strtotime($request->date)) == 03 || date('m', strtotime($request->date)) == 12) {
                    $cotisation->montant = $item['montant'] * 2;
                } else {
                    $cotisation->montant = $item['montant'];
                }
                $cotisation->numero_seance = $request->num_seance;
                $cotisation->users_id = $item['id'];
                $cotisation->save();
            }
            $montant_global = Cotisation::sum('montant');
            return response()->json(["success" => "Enregistrement éffectuer !", 'montant' => $montant_global]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function deletecotisations(Request $request)
    {
        try {
            Cotisation::find($request->id)->delete();
            return response()->json(["success" => "Suppression éffectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
}