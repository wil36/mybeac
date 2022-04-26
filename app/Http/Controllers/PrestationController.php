<?php

namespace App\Http\Controllers;

use App\Models\AyantDroit;
use App\Models\Caisse;
use App\Models\Prestation;
use App\Models\TypePrestation;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\DB;

class PrestationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.prestation');
    }

    public function getprestationList(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select pr.id, pr.created_at, pr.montant, DATE(pr.date) as dates, us.nom, us.prenom, tp.libelle, ad.nom as adnom, ad.prenom as adprenom from type_prestations tp, prestations pr, ayant_droits ad, users us where tp.id=pr.type_prestation_id and us.id=pr.users_id and ad.id=pr.ayant_droits_id'));
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
                    <span>PRE</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->dates . "</span>
                </div>
            </div>";
                })
                ->addColumn("montant", function ($data) {
                    return $data->montant . ' FCFA';
                })
                ->addColumn("membre", function ($data) {
                    return $data->nom . ' ' . $data->prenom;
                })
                ->addColumn("typePrestation", function ($data) {
                    return $data->libelle;
                })
                ->addColumn("ayantDroit", function ($data) {
                    return $data->adnom . ' ' . $data->adprenom;
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                    <a href="' . route('prestation.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
               <li class="nk-tb-action-hidden">
                    <a href="" data_id="' . $data->id . '" class="btn btn-trigger btn-icon delete-data-pres" data-toggle="tooltip" data-placement="top" title="Supprimer">
                       <em class="icon ni ni-trash"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('prestation.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                 
                                <li><a href="" class="delete-data-pres" data_id="' . $data->id . '"><em class="icon ni ni-trash"></em><span>Supprimer</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'date', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function getprestationListForUser(Request $request)
    {
        try {

            $data = DB::select(DB::raw('select pr.id, pr.created_at, pr.montant, DATE(pr.date) as dates, tp.libelle, ad.nom as adnom, ad.prenom as adprenom from type_prestations tp, prestations pr, ayant_droits ad, users us where tp.id=pr.type_prestation_id and us.id=pr.users_id and ad.id=pr.ayant_droits_id and pr.users_id=' . $request->id));
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
                    <span>PRE</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->dates . "</span>
                </div>
            </div>";
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })
                ->addColumn("typePrestation", function ($data) {
                    return $data->libelle;
                })
                ->addColumn("ayantDroit", function ($data) {
                    return $data->adnom . ' ' . $data->adprenom;
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                    <a href="' . route('prestation.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href="" data_id="' . $data->id . '" class="btn btn-trigger btn-icon delete-data-pres" data-toggle="tooltip" data-placement="top" title="Supprimer">
                       <em class="icon ni ni-trash"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('prestation.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                 
                                <li><a href="" class="delete-data-pres" data_id="' . $data->id . '"><em class="icon ni ni-trash"></em><span>Supprimer</span></a></li>
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            if ($request->id != null) {
                $membre = User::select('id', 'nom', 'prenom', 'matricule', 'tel', 'email', 'date_hadésion', 'nationalité', 'agence', 'sexe')->where('id', $request->id)->first();
                if ($membre == null) {
                    abort(404);
                }
                return view('pages.addprestation', ['membre' => $membre]);
            } else {
                abort(404);
            }
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $attributeNames = array(
                'typePrestation' => 'type de prestation',
                'listAyantDroit' => 'ayants droit',
            );
            $validator = FacadesValidator::make($request->all(), [
                'id' => ['required', 'integer'],
                'typePrestation' => ['required', 'string'],
                'montant' => ['required', 'numeric'],
                'date' => ['required', 'date', 'date_format:Y-m-d'],
                'listAyantDroit' => ['required', 'integer'],
            ]);
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            $montantCaissePrincipal = Caisse::select('principal')->first();
            if ($montantCaissePrincipal->principal < $request->montant) {
                return response()->json(["error" => "Le montant de la prestation est superieur au montant de la caisse actuel."]);
            }
            $nombrePrestation = Prestation::where('users_id', $request->id)->where('type_prestation_id', $request->typePrestation)->where('ayant_droits_id', $request->listAyantDroit)->count();
            if (isset($nombrePrestation) && $nombrePrestation > 0) {
                return response()->json(["error" => "Cette prestation a déjà été effectuée. Veuillez changer les informations"]);
            }
            DB::beginTransaction();
            $prestation  = new Prestation();
            $prestation['date'] = $request['date'];
            $prestation['montant'] = $request['montant'];
            $prestation['users_id'] = $request['id'];
            $prestation['type_prestation_id'] = explode('|', $request['typePrestation'])[0];
            $prestation['ayant_droits_id'] = $request['listAyantDroit'];

            $prestation->save();

            $caisse = Caisse::first();
            $caisse->principal = $caisse->principal - $request['montant'];
            $caisse->save();
            $type_prestation = TypePrestation::findOrFail($prestation['type_prestation_id']);
            if (isset($type_prestation)) {
                if ($type_prestation->delete_ayant_droit == '1') {
                    $ayant_droit = AyantDroit::findOrFail($prestation['ayant_droits_id']);
                    $ayant_droit->deces = 1;
                    $ayant_droit->save();
                }
            }
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if ($id != null) {
                $prestation = Prestation::find($id);
                $type_prestation = TypePrestation::find($prestation->type_prestation_id);
                $ayant_droits = AyantDroit::find($prestation->ayant_droits_id);
                $membre = User::select('id', 'nom', 'prenom', 'matricule', 'tel', 'email', 'date_hadésion', 'nationalité', 'agence', 'sexe')->where('id', $prestation->users_id)->first();
                // dd($membre);
                if ($membre == null) {
                    abort(404);
                }
                return view('pages.addprestation', ['membre' => $membre, 'prestation' => $prestation, 'type_prestation' => $type_prestation, 'ayant_droit' => $ayant_droits]);
            } else {
                abort(404);
            }
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $attributeNames = array(
                'typePrestation' => 'type de prestation',
                'listAyantDroit' => 'ayants droit',
            );
            $validator = FacadesValidator::make($request->all(), [
                'id' => ['required', 'integer'],
                'typePrestation' => ['required', 'string'],
                'montant' => ['required', 'numeric'],
                'date' => ['required', 'date', 'date_format:Y-m-d'],
                'listAyantDroit' => ['required', 'integer'],
            ]);
            // dd(date('Y-m-d', strtotime(now())));
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            DB::beginTransaction();
            $prestation  = Prestation::find($request->ids);
            $prestation['date'] = $request['date'];
            $prestation['montant'] = $request['montant'];
            $prestation['users_id'] = $request['id'];
            $prestation['type_prestation_id'] = explode('|', $request['typePrestation'])[0];
            $prestation['ayant_droits_id'] = $request['listAyantDroit'];

            $prestation->save();
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteprestation(Request $request)
    {
        try {
            Prestation::find($request->id)->delete();
            return response()->json(["success" => "Suppression éffectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
}