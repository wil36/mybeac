<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\Dons;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.dons');
    }

    /**
     * getDons donne la liste des dons de l'association effectuer par les membres ou des personnes eternes
     *
     * @param  mixed $request
     * @return void
     */
    public function getDons(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select do.id, do.sexe, do.nom, do.prenom, do.created_at, do.date, do.montant, do.email, do.type, do.montant from Dons do'));
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->editColumn("type", function ($data) {
                    return '<div class="user-card">
                    <div class="user-avatar bg-dim-primary d-none d-sm-flex">
                        <img class="object-cover w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name=' . $data->type . '&background=c7932b&color=fff" alt="" />
                    </div>
                    <div class="user-info">
                        <span class="tb-lead">' . $data->type . '</span>
                    </div>
                    </div>';
                })
                ->addColumn("nom", function ($data) {
                    return $data->nom;
                })
                ->addColumn("prenom", function ($data) {
                    return $data->prenom;
                })
                ->addColumn("sexe", function ($data) {
                    return $data->sexe;
                })
                ->addColumn("email", function ($data) {
                    return !isset($data->email) || $data->email == null ? 'Aucun' : $data->email;
                })
                ->addColumn("date", function ($data) {
                    return Carbon::parse($data->date)->format('d M Y');
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ') . ' FCFA';
                })
                ->editColumn("status", function ($data) {
                    return ' <td class="nk-tb-col tb-col-md">
                    <span class="badge badge-outline-success">Actif</span>
                </td>';
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
                      <li class="nk-tb-action-hidden">
                    <a href="" data_id="' . $data->id . '" class="btn btn-trigger btn-icon delete-data-dons" data-toggle="tooltip" data-placement="top" title="Supprimer">
                       <em class="icon ni ni-trash"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href="' . route('dons.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-left">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('membre.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                <li><a href="" class="delete-data-dons" data_id="' . $data->id . '"><em class="icon ni ni-trash"></em><span>Supprimer</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['type', 'Actions', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.add_dons');
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
                'montant1' => 'montant',
                'montant2' => 'montant',
                'id_membre' => 'membre',
            );
            if (isset($request->choix) && $request->choix == 1) {
                if (!isset($request->id_membre) || empty($request->id_membre) || $request->id_membre == "null") {
                    return response()
                        ->json(['error' => ['Veuillez sélectionner un membre']]);
                }
                $validator = FacadesValidator::make($request->all(), [
                    'id_membre' => ['required'],
                    'montant1' => ['required', 'numeric'],
                ]);
                $validator->setAttributeNames($attributeNames);
                if ($validator->fails()) {
                    return response()
                        ->json(['errors' => $validator->errors()->all()]);
                }
            } else {
                $validator = FacadesValidator::make($request->all(), [
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'email' => ['email', 'max:255'],
                    'sexe' => ['required', 'string', 'max:10'],
                    'montant2' => ['required', 'numeric'],
                ]);
                $validator->setAttributeNames($attributeNames);
                if ($validator->fails()) {
                    return response()
                        ->json(['errors' => $validator->errors()->all()]);
                }
            }
            DB::beginTransaction();
            $don = new Dons();
            if (isset($request->choix) && $request->choix == 1) {
                $user = User::findOrFail($request->id_membre);
                $don->nom = $user->nom;
                $don->prenom = $user->prenom;
                $don->email = $user->email;
                $don->montant = $request->montant1;
                $don->sexe = $user->sexe;
                $don->users_id = $user->id;
                $don->type = "interne";
                $don->date = Carbon::now();
                $don->save();
                $caisse = Caisse::first();
                $caisse->principal = $caisse->principal + $request->montant1;
                $caisse->save();
                DB::commit();
                return
                    response()->json(["success" => "Enregistrement éffectuer !"]);
            } else {
                $don->nom = $request->nom;
                $don->prenom = $request->prenom;
                $don->email = $request->email;
                $don->montant = $request->montant2;
                $don->sexe = $request->sexe;
                $don->date = Carbon::now();
                $don->users_id = null;
                $don->type = "externe";
                $don->save();
                $caisse = Caisse::first();
                $caisse->principal = $caisse->principal + $request->montant2;
                $caisse->save();
                DB::commit();
                return
                    response()->json(["success" => "Enregistrement éffectuer !"]);
            }
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
        $don = Dons::findOrFail($id);
        $membre = isset($don->users_id) && $don->type == 'interne' ?  User::select('matricule', 'nom', 'prenom')->where('id', '=', $don->users_id)->first() : null;
        return view('pages.add_dons', ['user' => $don->type == 'interne' ? $membre : null, 'don' => $don]);
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
                'montant1' => 'montant',
                'montant2' => 'montant',
                'id_membre' => 'membre',
            );
            if (isset($request->choix) && $request->choix == 1) {
                if (!isset($request->id_membre) || empty($request->id_membre) || $request->id_membre == "null") {
                    return response()
                        ->json(['error' => [$request->id_membre]]);
                }
                $validator = FacadesValidator::make($request->all(), [
                    'id_membre' => ['required'],
                    'montant1' => ['required', 'numeric'],
                ]);
                $validator->setAttributeNames($attributeNames);
                if ($validator->fails()) {
                    return response()
                        ->json(['errors' => $validator->errors()->all()]);
                }
            } else {
                $validator = FacadesValidator::make($request->all(), [
                    'nom' => ['required', 'string', 'max:255'],
                    'prenom' => ['required', 'string', 'max:255'],
                    'email' => ['email', 'max:255'],
                    'sexe' => ['required', 'string', 'max:10'],
                    'montant2' => ['required', 'numeric'],
                ]);
                $validator->setAttributeNames($attributeNames);
                if ($validator->fails()) {
                    return response()
                        ->json(['errors' => $validator->errors()->all()]);
                }
            }
            DB::beginTransaction();
            $don = Dons::findOrFail($request->id);
            $last_caisse = $don->montant;
            if (isset($request->choix) && $request->choix == 1) {
                $user = User::findOrFail($request->id_membre);
                $don->nom = $user->nom;
                $don->prenom = $user->prenom;
                $don->email = $user->email;
                $don->montant = $request->montant1;
                $don->sexe = $user->sexe;
                $don->users_id = $user->id;
                $don->type = "interne";
                $don->date = Carbon::now();
                $don->save();
                $caisse = Caisse::first();
                $caisse->principal = $caisse->principal - $last_caisse + $request->montant1;
                $caisse->save();
                DB::commit();
                return
                    response()->json(["success" => "Enregistrement éffectuer !"]);
            } else {
                $don->nom = $request->nom;
                $don->prenom = $request->prenom;
                $don->email = $request->email;
                $don->montant = $request->montant2;
                $don->sexe = $request->sexe;
                $don->date = Carbon::now();
                $don->users_id = null;
                $don->type = "externe";
                $don->save();

                //mise à jour de la caisse
                $caisse = Caisse::first();
                $caisse->principal = $caisse->principal - $last_caisse + $request->montant2;
                $caisse->save();
                DB::commit();
                return
                    response()->json(["success" => "Enregistrement éffectuer !"]);
            }
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
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();
            $don = Dons::findOrFail($request->id);
            $caisse = Caisse::first();
            $caisse->principal = $caisse->principal - $don->montant;
            $caisse->save();
            $don->delete();
            DB::commit();
            return response()->json(["success" => "Suppression éffectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDons(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select do.id, do.sexe, do.nom, do.prenom, do.created_at, do.date, do.montant,  do.email, do.type, do.montant from Dons do'));
            return view('pages.impressions.liste_des_dons', ['dons' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
}
