<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\Cotisation;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotisationController extends Controller
{

    /**
     * cotisation avoir l'interface de cotisation avec la liste des membre devant cotiser
     *
     * @return void
     */
    public function cotisation()
    {
        try {
            $montant_global = Cotisation::sum('montant');
            $last_numero_seance = DB::select(DB::raw('select max(numero_seance) as num from cotisations co where Month(co.date) < ' . Carbon::now()->format('m') . ' and Year(co.date) <= ' . Carbon::now()->format('Y')));
            return view('pages.cotisation', ['montant_global' => number_format($montant_global ?? 0, 0, ',', ' '), 'numero_seance' => number_format(isset($last_numero_seance[0]->num) ? $last_numero_seance[0]->num + 1 : 0 + 1, 0, ',', ' ')]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * historiqueAnnuelCotisation avoir l'interface de l'historique des cotisations annuelles
     *
     * @return void
     */
    public function historiqueAnnuelCotisation()
    {
        try {
            return view('pages.historiqueannuelcotisation');
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * historiqueMensuelCotisation avoir l'interface de l'historique des cotisations Mensuelles
     *
     * @return void
     */
    public function historiqueMensuelCotisation()
    {
        try {
            return view('pages.historiquemensuelcotisation');
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * historiqueMensuelCotisationDetailMembre avoir l'interface du detail des membre ayants cotiser durant un mois
     *
     * @return void
     */
    public function historiqueMensuelCotisationDetailMembre(Request $request)
    {
        try {
            return view('pages.listedesmembresdecotisationmensuel', ['dateDeRecherche' => $request->date]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * getUserCotisation liste des membres suceptible de cotiser durant le mois en cour
     *
     * @param  mixed $request
     * @return void
     */
    public function getUserCotisation(Request $request)
    {
        try {
            $month = Carbon::parse($request->date)->format('m');
            $year = Carbon::parse($request->date)->format('Y');
            // $data = User::with('category')->latest()->get();
            $data = DB::select(DB::raw('select us.id, us.nom, us.prenom, us.created_at, us.profile_photo_path, us.matricule, us.agence, us.tel, us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca where ca.id=us.categories_id and us.deces=false and us.retraite=false and us.exclut=false and us.id not in (select co.users_id from cotisations co where Month(co.date)=' . $month . ' and Year(co.date)=' . $year . ')'));
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

    /**
     * getUserDetailCotisationHistoriqueMensuel liste des utilisateurs ayant cotiser durant une date passé à partir du request
     *
     * @param  mixed $request
     * @return void
     */
    public function getUserDetailCotisationHistoriqueMensuel(Request $request)
    {
        try {
            $tabDate = explode('-', $request->date);
            $data = DB::select(DB::raw('select us.id, us.nom,us.sexe, us.prenom, us.created_at, us.profile_photo_path, us.matricule, us.agence, us.tel, us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca, cotisations co where ca.id=us.categories_id and us.id=co.users_id and Month(co.date)=' . (isset($tabDate[1]) ? $tabDate[1] : 0) . ' and Year(co.date)=' . (isset($tabDate[0]) ? $tabDate[0] : 0) . ''));
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
                ->addColumn("sexe", function ($data) {
                    return $data->sexe;
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

    /**
     * getHistoriqueAnnuelCotisation historique annuel ajax datatable
     *
     * @param  mixed $request
     * @return void
     */
    public function getHistoriqueAnnuelCotisation(Request $request)
    {
        try {
            $data = DB::select(DB::raw('SELECT Year(co.date) AS annee, SUM(co.montant) AS montant FROM cotisations co GROUP BY YEAR(co.date) ORDER BY YEAR(co.date) desc'));
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
     * getHistoriqueMensuelCotisation historique mensuel ajax datatable   
     *
     * @param  mixed $request
     * @return void
     */
    public function getHistoriqueMensuelCotisation(Request $request)
    {
        try {
            $data = DB::select(DB::raw("SELECT MONTH(co.date) AS mois, Year(co.date) AS annee, SUM(co.montant) AS montant, COUNT(co.users_id) AS nombre_user FROM cotisations co GROUP BY YEAR(co.date), MONTH(co.date) ORDER BY co.date desc"));

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
                    <a href="' . route('membre.historiquecotisationmensuelDetailMembre', $data->annee . '-' . $data->mois) . '" class="btn btn-trigger btn-icon delete-data-cot" data-toggle="tooltip" data-placement="top" title="Detail de la ligne">
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
     * getcotisationListForUser liste des cotisations d'un membre ajax datatable
     *
     * @param  mixed $request
     * @return void
     */
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

    /**
     * savecotisation enregistrement de la liste de cotisations des différents membres
     *
     * @param  mixed $request
     * @return void
     */
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
            $montantCotisationEnCour = 0;
            foreach ($request->liste as $item) {
                $cotisation = new Cotisation();
                $cotisation->date = $request->date;
                if (date('m', strtotime($request->date)) == 03 || date('m', strtotime($request->date)) == 12) {
                    $cotisation->montant = $item['montant'] * 2;
                    $montantCotisationEnCour += $item['montant'] * 2;
                } else {
                    $cotisation->montant = $item['montant'];
                    $montantCotisationEnCour += $item['montant'];
                }
                $cotisation->numero_seance = $request->num_seance;
                $cotisation->users_id = $item['id'];
                $cotisation->save();
            }
            $caisse = Caisse::first();
            $caisse->principal = $caisse->principal + $montantCotisationEnCour;
            $caisse->save();
            $montant_global = Cotisation::sum('montant');
            return response()->json(["success" => "Enregistrement éffectuer !", 'montant' => $montant_global]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * deletecotisations suppression d'une cotisation
     *
     * @param  mixed $request
     * @return void
     */
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