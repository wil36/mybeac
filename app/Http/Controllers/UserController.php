<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.users');
    }

    public function getUser(Request $request)
    {
        // $data = User::with('category')->latest()->get();
        $data = DB::select(DB::raw('select us.id,us.sexe, us.nom, us.prenom, us.created_at, us.profile_photo_path, us.matricule, us.agence, us.tel, us.nationalité, us.status, ca.montant, ca.libelle from users us, categories ca where ca.id=us.categories_id'));

        return \Yajra\DataTables\DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("id", function ($data) {
                return $data->id;
            })
            ->addColumn("updated_at", function ($data) {
                return $data->created_at;
            })
            ->editColumn("matricule", function ($data) {
                return "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                     <img class='object-cover w-8 h-8 rounded-full'
                                                            src=''
                                                            alt='$data->nom' />
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>$data->matricule</span>
                </div>
            </div>";
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
            ->addColumn("nationalité", function ($data) {
                return $data->nationalité;
            })
            ->addColumn("agence", function ($data) {
                return $data->agence;
            })
            // ->addColumn("email", function ($data) {
            //     return $data->email;
            // })
            ->addColumn("tel", function ($data) {
                return $data->tel;
            })
            ->addColumn("category", function ($data) {
                return $data->libelle;
            })
            // ->addColumn("dateNais", function ($data) {
            //     return $data->role;
            // })
            // ->addColumn("dateRecru", function ($data) {
            //     return $data->role;
            // })
            // ->addColumn("dateHade", function ($data) {
            //     return $data->role;
            // }) 
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
                    <a href="' . route('users.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href="' . route('user.delete', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Supprimer">
                       <em class="icon ni ni-trash"></em>
                    </a>
                </li>
                 <li class="nk-tb-action-hidden">
                    <a href="' . route('user.doubleauthdelete', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Supprimer la double authentification">
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
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('users.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                <li><a href="' . route('user.delete', $data->id) . '" ><em class="icon ni ni-trash"></em><span>Supprimer</span></a></li>
                                <li><a href="' . route('user.doubleauthdelete', $data->id) . '" ><em class="icon ni ni-reload-alt"></em><span>Supprimer la DA</span></a></li>
                                <li><a href="' . route('ayantsdroits.create', $data->id) . '" > <em class="icon ni ni-user-add-fill"></em><span>Ajouter un ayant droit</span></a></li>
                                <li><a href="' . route('prestation.create', $data->id) . '" > <em class="icon ni ni-property-add"></em><span>Ajouter une prestation</span></a></li>
                                <li><a href="' . route('membre.info', $data->id) . '" > <em class="icon ni ni-user-add-fill"></em><span>Detail du membre</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
            })->setRowClass("nk-tb-item")
            ->rawColumns(['matricule', 'Actions', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function infomembre(Request $request)
    {
        if ($request->id != null) {
            $membre = User::select('id', 'nom', 'prenom', 'matricule', 'tel', 'email', 'date_hadésion', 'nationalité', 'agence', 'sexe', 'categories_id', 'date_naissance', 'date_recrutement')->where('id', $request->id)->first();
            $cat = Category::find($membre->categories_id);
            return view('pages.informationmembre', ['membre' => $membre, 'category' => $cat]);
        } else {
            abort(404);
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
        $attributeNames = array(
            'email' => 'email',
            'role' => 'role',
            'tel' => 'numéro de téléphone',
            'dateNaissance' => 'date de naissance',
            'dateRecrutement' => 'date de recrutement',
            'dateHadhésion' => 'date d\'hadésion',
            'tel' => 'numéro de téléphone',
            'listCategorie' => 'Categorie',
        );
        $validator = FacadesValidator::make($request->all(), [
            'matricule' => ['required', 'max:255', 'unique:users,matricule'],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'nationalité' => ['required', 'string', 'max:255'],
            'sexe' => ['required', 'string', 'max:10'],
            'agence' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'tel' => ['required', 'string', 'max:25', 'unique:users,tel'],
            'dateNaissance' => ['required', 'date', 'date_format:Y-m-d'],
            'dateRecrutement' => ['required', 'date', 'date_format:Y-m-d'],
            'dateHadhésion' => ['required', 'date', 'date_format:Y-m-d'],
            'listCategorie' => ['required', 'integer'],
            'role' => ['required', 'string'],
        ]);
        // dd(date('Y-m-d', strtotime(now())));
        $validator->setAttributeNames($attributeNames);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $user  = new User();
            $user['matricule'] = $request['matricule'];
            $user['nom'] = $request['nom'];
            $user['prenom'] = $request['prenom'];
            $user['nationalité'] = $request['nationalité'];
            $user['agence'] = $request['agence'];
            $user['email'] = $request['email'];
            $user['tel'] = $request['tel'];
            $user['sexe'] = $request['sexe'];
            $user['date_naissance'] = date("Y-m-d", strtotime($request['dateNaissance']));
            $user['date_hadésion'] = date("Y-m-d", strtotime($request['dateRecrutement']));
            $user['date_recrutement'] = date("Y-m-d", strtotime($request['dateHadhésion']));
            $user['role'] = $request['role'];
            $user['categories_id'] = $request['listCategorie'];
            $user['theme'] = 0;
            $user['status'] = 1;
            $user['email_verified_at'] = now();
            $user['password'] = Hash::make('1234');

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
    public function edit(User $user)
    {
        $category = Category::select('code', 'libelle')->where('id', '=', $user->categories_id)->first();
        return view('pages.addusers', ['user' => $user, 'category' => $category]);
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
        $attributeNames = array(
            'email' => 'email',
            'role' => 'role',
            'tel' => 'numéro de téléphone',
            'dateNaissance' => 'date de naissance',
            'dateRecrutement' => 'date de recrutement',
            'dateHadhésion' => 'date d\'hadésion',
            'tel' => 'numéro de téléphone',
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
            'tel' => ['required', 'string', 'max:25', Rule::unique('users', 'tel')->where(function ($query) use ($request) {
                $query->where('id', '!=', $request['id']);
            })],
            'dateNaissance' => ['required', 'date', 'date_format:Y-m-d'],
            'dateRecrutement' => ['required', 'date', 'date_format:Y-m-d'],
            'dateHadhésion' => ['required', 'date', 'date_format:Y-m-d'],
            'listCategorie' => ['required', 'integer'],
            'role' => ['required', 'string'],
        ]);
        $validator->setAttributeNames($attributeNames);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $user = User::find($request['id']);
            $user['matricule'] = $request['matricule'];
            $user['nom'] = $request['nom'];
            $user['prenom'] = $request['prenom'];
            $user['nationalité'] = $request['nationalité'];
            $user['agence'] = $request['agence'];
            $user['email'] = $request['email'];
            $user['sexe'] = $request['sexe'];
            $user['tel'] = $request['tel'];
            $user['date_naissance'] = date("Y-m-d", strtotime($request['dateNaissance']));
            $user['date_hadésion'] = date("Y-m-d", strtotime($request['dateRecrutement']));
            $user['date_recrutement'] = date("Y-m-d", strtotime($request['dateHadhésion']));
            $user['role'] = $request['role'];
            $user['categories_id'] = $request['listCategorie'];

            $user->save();
            DB::commit();

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

    public function destroys($id)
    {
        User::find($id)->delete();
        return back()->with("status", "Suppression éffectuer avec succès");
    }

    public function doubleauthdelete($id)
    {
        $user = User::find($id)->first();
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->save();
        return back()->with("status", "Suppression de la double authentification éffectuer avec succès");
    }
}