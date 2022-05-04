<?php

namespace App\Http\Controllers;

use App\Models\AyantDroit;
use App\Models\Caisse;
use App\Models\Prestation;
use App\Models\TypePrestation;
use App\Models\User;
use Carbon\Carbon;
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

    /**
     * historiqueAnnuelPrestation avoir l'interface de l'historique des Prestations annuelles
     *
     * @return void
     */
    public function historiqueAnnuelPrestation()
    {
        try {
            return view('pages.historiqueannuelprestation');
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * historiqueMensuelPrestation avoir l'interface de l'historique des Prestations Mensuelles
     *
     * @return void
     */
    public function historiqueMensuelPrestation()
    {
        try {
            return view('pages.historiquemensuelprestation');
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * historiqueMensuelPrestationDetailMembre avoir l'interface du detail des membre ayants cotiser durant un mois
     *
     * @return void
     */
    public function historiqueMensuelPrestationDetailMembre(Request $request)
    {
        try {
            return view('pages.listedesmembresdeprestationmensuel', ['dateDeRecherche' => $request->date]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
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

    /**
     * getprestationListForUser
     *
     * @param  mixed $request
     * @return void
     */
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
     * getUserDetailPrestationHistoriqueMensuel liste des utilisateurs ayant cotiser durant une date passé à partir du request
     *
     * @param  mixed $request
     * @return void
     */
    public function getUserDetailPrestationHistoriqueMensuel(Request $request)
    {
        try {
            $tabDate = explode('-', $request->date);
            $data = DB::select(DB::raw('select us.id, us.nom, us.prenom, us.profile_photo_path, us.created_at, us.matricule, SUM(po.users_id) AS nb_prest, SUM(po.montant) as montant from users us, prestations po where us.id=po.users_id and Month(po.date)=' . (isset($tabDate[1]) ? $tabDate[1] : 0) . ' and Year(po.date)=' . (isset($tabDate[0]) ? $tabDate[0] : 0) . ' group by po.users_id'));
            // $montant = DB::select(DB::raw('select sum(po.montant) as montant from users us, prestations po where us.id=po.users_id and Month(po.date)=' . (isset($tabDate[1]) ? $tabDate[1] : 0) . ' and Year(po.date)=' . (isset($tabDate[0]) ? $tabDate[0] : 0) . ''));
            //TODO : Afficher le montant total de la prestation pour chaque utilisateur
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
                    if (isset($data->profile_photo_path)) {
                        return '<div class="user-card">
                <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                     <img class="object-cover w-8 h-8 rounded-full popup-image" data-toggle="modal" data-target="#view-photo-modal" src="' . asset('picture_profile/' . $data->profile_photo_path)  . '" alt="" />
                </div>
                <div class="user-info">
                    <span class="tb-lead">' . $data->matricule . '</span>
                </div>
            </div>';
                    } else {
                        return '<div class="user-card">
                <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                     <img class="object-cover w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=' . $data->nom . '&background=c7932b&color=fff" alt="" />
                </div>
                <div class="user-info">
                    <span class="tb-lead">' . $data->matricule . '</span>
                </div>
            </div>';
                    }
                })
                ->addColumn("nom", function ($data) {
                    return $data->nom;
                })
                // ->addColumn("montant", function ($data) {
                //     return number_format($data->montant, 0, ',', ' ');
                // })
                ->addColumn("prenom", function ($data) {
                    return $data->prenom;
                })
                ->addColumn("nb_prestation", function ($data) {
                    return  number_format($data->nb_prest, 0, ',', ' ');
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ') . " FCFA";
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
               </li>
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('membre.info', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Detail du membre">
                      <em class="icon ni ni-expand"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"></a>
                        <div class="dropdown-menu dropdown-menu-right">
                           
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['matricule', 'select',  'Actions',])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }


    /**
     * getHistoriqueAnnuelPrestation historique annuel ajax datatable
     *
     * @param  mixed $request
     * @return void
     */
    public function getHistoriqueAnnuelPrestation(Request $request)
    {
        try {
            $data = DB::select(DB::raw('SELECT Year(po.date) AS annee, SUM(po.montant) AS montant FROM Prestations po GROUP BY YEAR(po.date) ORDER BY YEAR(po.date) desc'));
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->editColumn("annee", function ($data) {
                    return "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
               HA
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>$data->annee</span>
                </div>
            </div>";
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ') . ' FCFA';
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                   
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['annee', 'Actions'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * getHistoriqueMensuelPrestattion historique mensuel ajax datatable   
     *
     * @param  mixed $request
     * @return void
     */
    public function getHistoriqueMensuelPrestation(Request $request)
    {
        try {
            $data = DB::select(DB::raw("SELECT MONTH(po.date) AS mois, Year(po.date) AS annee, SUM(po.montant) AS montant, COUNT(po.users_id) AS nombre_user FROM Prestations po GROUP BY YEAR(po.date), MONTH(po.date) ORDER BY po.date desc"));

            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->editColumn("annee", function ($data) {
                    return "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
               HM
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>$data->annee</span>
                </div>
            </div>";
                })
                ->addColumn("mois", function ($data) {
                    $date = Carbon::create()->day(1)->month($data->mois);
                    return $date->Format('M');
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ') . ' FCFA';
                })
                ->addColumn("nb_user", function ($data) {
                    return number_format($data->nombre_user, 0, ',', ' ');
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
                     <li class="nk-tb-action-hidden">
                    <a href="' . route('membre.historiqueprestationmensuelDetailMembre', $data->annee . '-' . $data->mois) . '" class="btn btn-trigger btn-icon delete-data-cot" data-toggle="tooltip" data-placement="top" title="Detail de la ligne">
                       <em class="icon ni ni-expand"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                   
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['annee', 'Actions'])
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
                $membre = User::select('id', 'nom', 'prenom', 'matricule', 'tel', 'email', 'date_hadésion', 'nationalité', 'agence', 'sexe', 'profile_photo_path')->where('id', $request->id)->first();
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