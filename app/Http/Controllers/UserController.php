<?php

namespace App\Http\Controllers;

use App\Models\AyantDroit;
use App\Models\Category;
use App\Models\Cotisation;
use App\Models\Prestation;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        try {
            $membre = Auth::user();
            $category = Category::select('code', 'libelle')->where('id', '=', $membre->categories_id)->first();
            return view('profile.update_profile', ['user' => $membre, 'category' => $category]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request, User $user)
    {
        try {
            $attributeNames = array(
                'email' => 'email',
                'role' => 'role',
                'dateNaissance' => 'date de naissance',
                'dateHadhésion' => 'date d\'hadésion',
                'status_matrimonial' => 'statut matrimonial',
                'listCategorie' => 'Categorie',
            );
            $validator = FacadesValidator::make($request->all(), [
                'matricule' => ['required', 'max:255', Rule::unique('users', 'matricule')->where(function ($query) use ($request) {
                    $query->where('id', '!=', $request['id']);
                })],
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'nationalité' => ['required', 'string', 'max:255'],
                'sexe' => ['required', 'string', 'max:10'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->where(function ($query) use ($request) {
                    $query->where('id', '!=', $request['id']);
                })],
                'dateNaissance' => ['required', 'date', 'date_format:Y-m-d'],
                'status_matrimonial' => [
                    'required',
                    'string'
                ],
            ]);
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            DB::beginTransaction();
            $user = User::find($request['id']);
            $user['matricule'] = $request['matricule'];
            $user['nom'] = $request['nom'];
            $user['prenom'] = $request['prenom'];
            $user['nationalité'] = $request['nationalité'];
            $user['email'] = $request['email'];
            $user['sexe'] = $request['sexe'];
            $user['date_naissance'] = date("Y-m-d", strtotime($request['dateNaissance']));
            $user['status_matrimonial'] = $request['status_matrimonial'];
            $expath = $user['profile_photo_path'];
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                $path3 = $request->file('picture')->store('public/images/picture_profile');
                $user['profile_photo_path'] = basename($path3);
            }
            $user->save();

            DB::commit();
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                if (Storage::exists('public/images/picture_profile/' . $expath)) {
                    Storage::delete('public/images/picture_profile/' . $expath);
                }
            }
            return response()->json(["success" => "Modification éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }


    public function changePassword()
    {
        try {
            return view('profile.update_password');
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        try {
            $attributeNames = array(
                'password' => 'Nouveau Mot de passe',
                'current_password' => 'Mot de passe actuel',
            );
            $validator = FacadesValidator::make($request->all(), [
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:4'],
            ]);
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            $user = User::findOrFail(Auth::user()->id);
            if (!isset($request['current_password']) || !Hash::check($request['current_password'], $user->password)) {
                return response()->json(["error" => "Le mot de passe actuel est incorect"]);
            }
            $user->password = Hash::make($request['password']);
            $user->save();
            return response()->json(["success" => "Modification éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(
                ["error" => $e->getMessage()]
            );
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.users');
    }

    /**
     * getUser donne la liste des membres de l'association qui ne sont pas exclut, décédé, ou encore qui ne sont pas en retraite
     *
     * @param  mixed $request
     * @return void
     */
    public function getUser(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select us.id,us.sexe, us.nom, us.prenom, us.status_matrimonial, us.created_at, us.profile_photo_path, us.matricule, us.agence,  us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca where ca.id=us.categories_id and us.deces=0 and us.retraite=0 and us.exclut=0'));
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
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
                ->addColumn("prenom", function ($data) {
                    return $data->prenom;
                })
                ->addColumn("sexe", function ($data) {
                    return $data->sexe;
                })
                ->addColumn("status_matrimonial", function ($data) {
                    return $data->status_matrimonial;
                })
                ->addColumn("nationalité", function ($data) {
                    return $data->nationalité;
                })
                ->addColumn("agence", function ($data) {
                    return $data->agence;
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
               
                <li class="nk-tb-action-hidden">
                    <a href="' . route('membre.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href=""  data_id="' . $data->id . '" class="btn btn-trigger btn-icon exclure-data-user" data-toggle="tooltip" data-placement="top" title="Exclure le membre">
                      <em class="icon ni ni-user-cross-fill"></em>
                    </a>
                </li>
                 <li class="nk-tb-action-hidden">
                    <a href=""  data_id="' . $data->id . '" class="btn btn-trigger btn-icon deces-data-user" data-toggle="tooltip" data-placement="top" title="Activer le decès">
                       <em class="icon ni ni-check-thick" style="color:red;"></em>

                    </a>
                </li>
                 <li class="nk-tb-action-hidden">
                    <a href=""  data_id="' . $data->id . '" class="btn btn-trigger btn-icon retraite-data-user" data-toggle="tooltip" data-placement="top" title="Activer la retraite">
                      <em class="icon ni ni-check-thick" style="color:orange;"></em>
                    </a>
                </li>
                 <li class="nk-tb-action-hidden">
                    <a href="" data_id="' . $data->id . '" class="btn btn-trigger btn-icon dbauth-delete" data-toggle="tooltip" data-placement="top" title="Supprimer la double authentification">
                        <em class="icon ni ni-reload-alt"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href="' . route('ayantsdroits.create', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Ajouter un ayant droit">
                      <em class="icon ni ni-user-add-fill"></em>
                    </a>
                </li>
                 <li class="nk-tb-action-hidden">
                    <a href="' . route('prestation.create', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Ajouter une prestation">
                      <em class="icon ni ni-property-add"></em>
                    </a>
                </li>
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('membre.info', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Detail du membre">
                      <em class="icon ni ni-expand"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-left">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('membre.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                <li><a href="" class="exclure-data-user" data_id="' . $data->id . '" ><em class="icon ni ni-user-cross-fill"></em><span>Exclure le membre</span></a></li>
                                <li><a href="" class="deces-data-user" data_id="' . $data->id . '" ><em class="icon ni ni-check-thick" style="color:red;"></em><span>Activer le decès</span></a></li>
                                <li><a href="" class="retraite-data-user" data_id="' . $data->id . '" ><em class="icon ni ni-check-thick" style="color:orange;"></em><span>Activer la retraite</span></a></li>
                                <li><a href="" class="dbauth-delete" data_id="' . $data->id . '" ><em class="icon ni ni-reload-alt"></em><span>Supprimer la double authentification</span></a></li>
                                <li><a href="' . route('ayantsdroits.create', $data->id) . '" > <em class="icon ni ni-user-add-fill"></em><span>Ajouter un ayant droit</span></a></li>
                                <li><a href="' . route('prestation.create', $data->id) . '" > <em class="icon ni ni-property-add"></em><span>Ajouter une prestation</span></a></li>
                                <li><a href="' . route('membre.info', $data->id) . '" > <em class="icon ni ni-expand"></em><span>Detail du membre</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['matricule', 'Actions', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function getMembreDecede()
    {
        return view('pages.liste_des_membres_decede');
    }

    /**
     * getMembreDecedeAjax liste des utilisateurs décédés
     *
     * @param  mixed $request
     * @return void
     */
    public function getMembreDecedeAjax(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select us.id, us.nom,us.sexe, us.prenom, us.created_at, us.profile_photo_path, us.matricule, us.agence,  us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca where ca.id=us.categories_id and us.deces = 1'));
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
                ->addColumn("sexe", function ($data) {
                    return $data->sexe;
                })
                ->addColumn("category", function ($data) {
                    return $data->libelle;
                })
                ->editColumn("status", function ($data) {

                    return ' <td class="nk-tb-col tb-col-md">
                <span class="badge badge-outline-success">Décédé</span>
            </td>';
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('membre.info', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Detail du membre">
                      <em class="icon ni ni-expand"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-left">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('membre.info', $data->id) . '" > <em class="icon ni ni-expand"></em><span>Detail du membre</span></a></li>
                            </ul>
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

    public function getMembreRetraite()
    {
        return view('pages.liste_des_membres_retraite');
    }

    /**
     * getMembreDecedeAjax liste des utilisateurs retraités
     *
     * @param  mixed $request
     * @return void
     */
    public function getMembreRetraiteAjax(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select us.id, us.nom,us.sexe, us.prenom, us.created_at, us.profile_photo_path, us.matricule, us.agence,  us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca where ca.id=us.categories_id and us.retraite = 1'));
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
                ->addColumn("sexe", function ($data) {
                    return $data->sexe;
                })
                ->addColumn("category", function ($data) {
                    return $data->libelle;
                })
                ->editColumn("status", function ($data) {

                    return ' <td class="nk-tb-col tb-col-md">
                <span class="badge badge-outline-success">Retraité</span>
            </td>';
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('membre.info', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Detail du membre">
                      <em class="icon ni ni-expand"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-left">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('membre.info', $data->id) . '" > <em class="icon ni ni-expand"></em><span>Detail du membre</span></a></li>
                            </ul>
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

    public function getMembreExclus()
    {
        return view('pages.liste_des_membres_exclut');
    }

    /**
     * getMembreDecedeAjax liste des utilisateurs retraités
     *
     * @param  mixed $request
     * @return void
     */
    public function getMembreExclusAjax(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select us.id, us.nom,us.sexe, us.prenom, us.created_at, us.profile_photo_path, us.matricule, us.agence, us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca where ca.id=us.categories_id and us.exclut = 1'));
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
                ->addColumn("sexe", function ($data) {
                    return $data->sexe;
                })
                ->addColumn("category", function ($data) {
                    return $data->libelle;
                })
                ->editColumn("status", function ($data) {

                    return ' <td class="nk-tb-col tb-col-md">
                <span class="badge badge-outline-success">Exclus</span>
            </td>';
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                    <a href=""  data_id="' . $data->id . '" class="btn btn-trigger btn-icon inclure-data-user" data-toggle="tooltip" data-placement="top" title="inclure le membre">
                     <em class="icon ni ni-user-check-fill"></em>
                    </a>
                </li>
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('membre.info', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Detail du membre">
                      <em class="icon ni ni-expand"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-left">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="" class="inclure-data-user" data_id="' . $data->id . '" ><em class="icon ni ni-user-check-fill"></em><span>Inclure le membre</span></a></li>
                                <li><a href="' . route('membre.info', $data->id) . '" > <em class="icon ni ni-expand"></em><span>Detail du membre</span></a></li>
                            </ul>
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function infomembre(Request $request)
    {
        try {
            if ($request->id != null) {
                $membre = User::select('id', 'nom', 'prenom', 'matricule',  'email', 'date_hadésion', 'nationalité', 'agence', 'sexe', 'categories_id', 'date_naissance',  'profile_photo_path')->where('id', $request->id)->first();
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

    /**
     * getCategories recupération des membres par ajax
     *
     * @param  mixed $request
     * @return void
     */
    public function getUserAjaxCombobox(Request $request)
    {
        try {
            $data = User::select("id", "matricule", "nom", "prenom")->where(function ($query) use ($request) {
                $query->where('matricule', 'like', '%' . $request->search . '%');
            })->orWhere(function ($query) use ($request) {
                $query->where(
                    'nom',
                    'like',
                    '%' . $request->search . '%'
                );
            })->orWhere(function ($query) use ($request) {
                $query->where(
                    'prenom',
                    'like',
                    '%' . $request->search . '%'
                );
            })->limit(5)->get();
            $resp = array();
            foreach ($data as $e) {
                $resp[] = array('id' => $e->id, 'text' => $e->matricule . "   |  " . $e->nom . ' ' . $e->prenom, 'value' => $e->id);
            }
            return response()->json($resp);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.addusers');
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
                'email' => 'email',
                'role' => 'role',
                'dateNaissance' => 'date de naissance',
                'dateHadhésion' => 'date d\'hadésion',
                'listCategorie' => 'categorie',
                'type_parent' => 'etat de vie des parents',
                'status_matrimonial' => 'statut matrimonial'
            );
            $validator = FacadesValidator::make($request->all(), [
                'matricule' => ['required', 'max:255', 'unique:users,matricule'],
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'nationalité' => ['required', 'string', 'max:255'],
                'sexe' => ['required', 'string', 'max:10'],
                'agence' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'dateNaissance' => ['required', 'date', 'date_format:Y-m-d'],
                'dateHadhésion' => ['required', 'date', 'date_format:Y-m-d'],
                'listCategorie' => ['required', 'integer'],
                'role' => ['required', 'string'],
                'type_parent' => ['required', 'integer'],
                'status_matrimonial' => ['required', 'string', 'max:50'],
            ]);
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            DB::beginTransaction();
            $user  = new User();
            $user['matricule'] = $request['matricule'];
            $user['nom'] = $request['nom'];
            $user['prenom'] = $request['prenom'];
            $user['nationalité'] = $request['nationalité'];
            $user['agence'] = $request['agence'];
            $user['email'] = $request['email'];
            $user['sexe'] = $request['sexe'];
            $user['date_naissance'] = date("Y-m-d", strtotime($request['dateNaissance']));
            $user['date_hadésion'] = date("Y-m-d", strtotime($request['dateHadhésion']));
            $user['role'] = $request['role'];
            $user['categories_id'] = $request['listCategorie'];
            $user['type_parent'] = $request['type_parent'];
            $user['status_matrimonial'] = $request['status_matrimonial'];
            $user['theme'] = 0;
            $user['status'] = 1;
            $user['email_verified_at'] = now();
            $user['password'] = Hash::make('1234');
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                $path3 = $request->file('picture')->store('public/images/picture_profile');
                $user['profile_photo_path'] = basename($path3);
            }

            $user->save();
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $membre)
    {
        try {
            $category = Category::select('code', 'libelle')->where('id', '=', $membre->categories_id)->first();
            return view('pages.addusers', ['user' => $membre, 'category' => $category]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $attributeNames = array(
                'email' => 'email',
                'role' => 'role',
                'dateNaissance' => 'date de naissance',
                'dateHadhésion' => 'date d\'hadésion',
                'status_matrimonial' => 'statut matrimonial',
                'listCategorie' => 'Categorie',
            );
            $validator = FacadesValidator::make($request->all(), [
                'matricule' => ['required', 'max:255', Rule::unique('users', 'matricule')->where(function ($query) use ($request) {
                    $query->where('id', '!=', $request['id']);
                })],
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'nationalité' => ['required', 'string', 'max:255'],
                'agence' => ['required', 'string', 'max:255'],
                'sexe' => ['required', 'string', 'max:10'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->where(function ($query) use ($request) {
                    $query->where('id', '!=', $request['id']);
                })],
                'dateNaissance' => ['required', 'date', 'date_format:Y-m-d'],
                'dateHadhésion' => ['required', 'date', 'date_format:Y-m-d'],
                'listCategorie' => ['required', 'integer'],
                'role' => ['required', 'string'],
                'status_matrimonial' => [
                    'required',
                    'string'
                ],
                'type_parent' => ['required', 'integer'],
                // 'picture' => ['max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf']
            ]);
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            DB::beginTransaction();
            $user = User::find($request['id']);
            $user['matricule'] = $request['matricule'];
            $user['nom'] = $request['nom'];
            $user['prenom'] = $request['prenom'];
            $user['nationalité'] = $request['nationalité'];
            $user['agence'] = $request['agence'];
            $user['email'] = $request['email'];
            $user['sexe'] = $request['sexe'];
            $user['date_naissance'] = date("Y-m-d", strtotime($request['dateNaissance']));
            $user['date_hadésion'] = date("Y-m-d", strtotime($request['dateHadhésion']));
            $user['role'] = $request['role'];
            $user['type_parent'] = $request['type_parent'];
            $user['status_matrimonial'] = $request['status_matrimonial'];
            $user['categories_id'] = $request['listCategorie'];
            $expath = $user['profile_photo_path'];
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                $path3 = $request->file('picture')->store('public/images/picture_profile');
                $user['profile_photo_path'] = basename($path3);
            }
            $user->save();
            if ($request['type_parent'] == '0') {
                $ayantsdroits = AyantDroit::where('users_id', $request['id'])->where('statut', 'Tuteur')->get();
                foreach ($ayantsdroits as $ad) {
                    $ad['deces'] = 1;
                    $ad->save();
                }
            } else {
                $ayantsdroits = AyantDroit::where('users_id', $request['id'])->where('statut', 'Parent')->get();
                foreach ($ayantsdroits as $ad) {
                    $ad['deces'] = 1;
                    $ad->save();
                }
            }

            DB::commit();
            if ($request->hasFile('picture') && $request->file('picture')->isValid()) {
                if (Storage::exists('public/images/picture_profile/' . $expath)) {
                    Storage::delete('public/images/picture_profile/' . $expath);
                }
            }
            return response()->json(["success" => "Modification éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    // public function destroy(User $user)
    // {
    //     User::find($user->id)->delete();
    //     return back()->with("status", "Suppression éffectuer avec succès");
    // }

    public function excluremembre(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->exclut = 1;
            $user->save();
            return response()->json(["success" => "Effectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function incluremembre(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->exclut = 0;
            $user->save();
            return response()->json(["success" => "Effectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function decesmembre(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->deces = 1;
            $user->save();
            return response()->json(["success" => "Effectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function retraitemembre(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            $user->retraite = 1;
            $user->save();
            return response()->json(["success" => "Effectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Deletes the two-factor authentication information for a user.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse A JSON response indicating success or failure.
     */
    public function doubleauthdelete(Request $request)
    {
        try {
            // Find the user by ID.
            $user = User::findOrFail($request->id);

            // Clear the two-factor authentication information.
            $user->two_factor_secret = null;
            $user->two_factor_recovery_codes = null;
            $user->save();

            // Return a success response.
            return response()->json([
                "success" => "Suppression de la double authentification effectuée avec succès"
            ]);
        } catch (Exception $e) {
            // Return an error response.
            return response()->json([
                "error" => "Une erreur s'est produite."
            ]);
        }
    }

    /**
     * Returns a view with a list of members and their information.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function impressionListMembre(Request $request)
    {
        try {
            $data = DB::table('users')
                ->join('categories', 'categories.id', '=', 'users.categories_id')
                ->select('users.id', 'users.sexe', 'users.nom', 'users.prenom', 'users.status_matrimonial', 'users.created_at', 'users.profile_photo_path', 'users.matricule', 'users.agence', 'users.nationalité', 'users.status', 'categories.montant', 'categories.libelle')
                ->where('users.deces', '=', 0)
                ->where('users.retraite', '=', 0)
                ->where('users.exclut', '=', 0)
                ->get();

            return view('pages.impressions.liste_des_mebres', ['membres' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Retrieves a list of deceased members.
     *
     * @param Request $request the HTTP request data.
     * @throws Exception if an error occurs.
     * @return \Illuminate\Contracts\View\View the list of deceased members.
     */
    public function impressionListMembreDecede(Request $request)
    {
        $data = DB::table('users')
            ->join('categories', 'categories.id', '=', 'users.categories_id')
            ->select('users.id', 'users.sexe', 'users.nom', 'users.prenom', 'users.status_matrimonial', 'users.created_at', 'users.profile_photo_path', 'users.matricule', 'users.agence', 'users.nationalité', 'users.status', 'categories.montant', 'categories.libelle')
            ->where('users.deces', '=', 1)
            ->where('users.retraite', '=', 0)
            ->where('users.exclut', '=', 0)
            ->get();

        return view('pages.impressions.liste_des_membres_decedes', ['membres' => $data]);
    }

    /**
     * Returns a list of excluded members' information for display
     *
     * @param Request $request The request object
     *
     * @return View|Response The view containing the list of excluded members or a JSON response with an error message
     */
    public function impressionListMembreExclut(Request $request)
    {
        try {
            $data = DB::table('users')
                ->select(
                    'users.id',
                    'users.sexe',
                    'users.nom',
                    'users.prenom',
                    'users.status_matrimonial',
                    'users.created_at',
                    'users.profile_photo_path',
                    'users.matricule',
                    'users.agence',
                    'users.nationalité',
                    'users.status',
                    'categories.montant',
                    'categories.libelle'
                )
                ->join('categories', 'categories.id', '=', 'users.categories_id')
                ->where('users.deces', '=', 0)
                ->where('users.retraite', '=', 0)
                ->where('users.exclut', '=', 1)
                ->get();
            return view('pages.impressions.liste_des_membres_excluts', ['membres' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "An error occurred."]);
        }
    }


    /**
     * Retrieves a list of retired members for display on the 'liste_des_membres_retraites' page.
     *
     * @param Request $request The HTTP request object.
     * @throws Some_Exception_Class description of exception
     * @return Illuminate\View\View Returns a view populated with data from the database query.
     */
    public function impressionListMembreRetraite(Request $request)
    {
        $data = DB::table('users')
            ->join('categories', 'categories.id', '=', 'users.categories_id')
            ->select(
                'users.id',
                'users.sexe',
                'users.nom',
                'users.prenom',
                'users.status_matrimonial',
                'users.created_at',
                'users.profile_photo_path',
                'users.matricule',
                'users.agence',
                'users.nationalité',
                'users.status',
                'categories.montant',
                'categories.libelle'
            )
            ->where('users.deces', '=', 0)
            ->where('users.retraite', '=', 1)
            ->where('users.exclut', '=', 0)
            ->get();
        return view('pages.impressions.liste_des_membres_retraites', ['membres' => $data]);
    }
}
