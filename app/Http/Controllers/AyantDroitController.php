<?php

namespace App\Http\Controllers;

use App\Models\AyantDroit;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AyantDroitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function getAyantsDroit(Request $request)
    {

        try {
            $data = AyantDroit::select("id", "nom", "prenom", "statut")->where(function ($query) use ($request) {
                $query->where('nom', 'like', '%' . $request->search . '%')->where('users_id', $request->id)->where('deces', '!=', '1');
            })->orWhere(function ($query) use ($request) {
                $query->where('prenom', 'like', '%' . $request->search . '%')->where('users_id', $request->id)->where('deces', '!=', '1');
            })
                ->limit(5)->get();
            $resp = array();
            foreach ($data as $e) {
                $resp[] = array('id' => $e->id, 'text' => $e->nom . ' ' . $e->prenom . ' | ' . $e->statut, 'value' => $e->id);
            }
            return response()->json($resp);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function ayantsdroitsListForUser(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select id, nom, prenom, cni, acte_naissance, certificat_vie, created_at, statut from ayant_droits where users_id=' . $request->id . ' and deces <> 1'));
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->editColumn("nom", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>AYD</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->nom . ' ' . $data->prenom . "</span>
                </div>
            </div>";
                })
                ->addColumn("liens", function ($data) {
                    return $data->statut;
                })
                ->addColumn("cni", function ($data) {
                    return isset($data->cni) ?  '<a class="btn" href="' . asset('storage/cni/' . $data->cni) . '" ><em class="icon ni ni-download"></em></a>' : 'Aucun';
                })
                ->addColumn("acte_naissance", function ($data) {
                    return isset($data->acte_naissance) ?  '<a class="btn btn-btn" href="' . asset('storage/acte_naissance/' . $data->acte_naissance) . '" > <em class="icon ni ni-download"></em></a>' : 'Aucun';
                })
                ->addColumn("certificat_vie", function ($data) {
                    return isset($data->certificat_vie) ?  '<a class="btn btn-btn" href="' . asset('storage/certificat_vie/' . $data->certificat_vie) . '" > <em class="icon ni ni-download"></em></a>' : 'Aucun';
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                    <a href="' . route('ayantsdroits.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href="" data_id="' . $data->id . '" class="btn btn-trigger btn-icon delete-data-ayant" data-toggle="tooltip" data-placement="top" title="Supprimer">
                       <em class="icon ni ni-trash"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('ayantsdroits.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                 
                                <li><a href="" class="delete-data-ayant" data_id="' . $data->id . '"><em class="icon ni ni-trash"></em><span>Supprimer</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['nom', 'Actions', 'cni', 'certificat_vie', 'acte_naissance'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }


    public function ayantsdroitsListForUserdecede(Request $request)
    {
        try {
            $data = DB::select(DB::raw('select id, nom, prenom, cni, acte_naissance, certificat_vie, created_at, statut from ayant_droits where users_id=' . $request->id . ' and deces = 1'));
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->editColumn("nom", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>AYD</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->nom . ' ' . $data->prenom . "</span>
                </div>
            </div>";
                })
                ->addColumn("liens", function ($data) {
                    return $data->statut;
                })
                ->addColumn("cni", function ($data) {
                    return isset($data->cni) ?  '<a class="btn" href="' . asset('storage/cni/' . $data->cni) . '" ><em class="icon ni ni-download"></em></a>' : 'Aucun';
                })
                ->addColumn("acte_naissance", function ($data) {
                    return isset($data->acte_naissance) ?  '<a class="btn btn-btn" href="' . asset('storage/acte_naissance/' . $data->acte_naissance) . '" > <em class="icon ni ni-download"></em></a>' : 'Aucun';
                })
                ->addColumn("certificat_vie", function ($data) {
                    return isset($data->certificat_vie) ?  '<a class="btn btn-btn" href="' . asset('storage/certificat_vie/' . $data->certificat_vie) . '" > <em class="icon ni ni-download"></em></a>' : 'Aucun';
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                    <a href="' . route('ayantsdroits.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href="" data_id="' . $data->id . '" class="btn btn-trigger btn-icon active-data-ayant" data-toggle="tooltip" data-placement="top" title="Activer">
                       <em class="icon ni ni-regen"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('ayantsdroits.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                 
                                <li><a href="" class="active-data-ayant" data_id="' . $data->id . '"><em class="icon ni ni-regen"></em><span>Activer</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['nom', 'Actions', 'cni', 'certificat_vie', 'acte_naissance'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function ayantdroitactive(Request $request)
    {
        try {
            $ayantsdroits = AyantDroit::find($request->id);
            $ayantsdroits->deces = 0;
            $ayantsdroits->save();
            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        try {
            $user = User::select('nom', 'matricule', 'prenom', 'email', 'type_parent')->where('id', $id)->first();
            return view('pages.addayantsdroits', ['father' => $user, 'id' => $id]);
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
        $validator = FacadesValidator::make($request->all(), [
            'id' => ['required', 'exists:users,id'],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'statut' => ['required', 'string', 'max:50'],
            // 'cni' => ['nullable', 'max:2048', 'file', 'mimes:jpeg,jpg,png,doc,docs,pdf'],
            // 'acte_naissance' => ['nullable', 'max:2048', 'file', 'mimes:jpeg,jpg,png,doc,docs,pdf'],
            // 'certificat_vie' => ['nullable', 'max:2048', 'file', 'mimes:jpeg,png,jpg,doc,docs,pdf'],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        if ($request['statut'] == 'Parent' || $request['statut'] == 'Tuteur') {
            if ($request->hasFile('cni') == false) {
                return response()
                    ->json(['errors' => ["La cni est obligatoire."]]);
            }
        }
        if ($request['statut'] == 'Enfant') {
            if ($request->hasFile('acte_naissance') == false) {
                return response()
                    ->json(['errors' => ["L'acte de naissance est obligatoire."]]);
            }
        }
        if ($request->hasFile('certificat_vie') == false) {
            return response()
                ->json(['errors' => ["Le certificat de vie est obligatoire."]]);
        }
        try {
            DB::beginTransaction();
            $ayantsdroits  = new AyantDroit();
            $ayantsdroits['nom'] = $request['nom'];
            $ayantsdroits['prenom'] = $request['prenom'];
            $ayantsdroits['statut'] = $request['statut'];
            $ayantsdroits['users_id'] = $request['id'];
            if ($request->hasFile('cni') && $request->file('cni')->isValid()) {
                // $name = time() . '' . uniqid() . '.' . $request()->file('cni')->getClientOriginalExtension();
                $path1 = $request->file('cni')->store('public/images/ayantdroit/cni');
                $ayantsdroits['cni'] = basename($path1);
            }
            if ($request->hasFile('acte_naissance') && $request->file('acte_naissance')->isValid()) {
                $path2 = $request->file('acte_naissance')->store('public/images/ayantdroit/acte_naissance');
                $ayantsdroits['acte_naissance'] = basename($path2);
            }
            if ($request->hasFile('certificat_vie') && $request->file('certificat_vie')->isValid()) {
                $path3 = $request->file('certificat_vie')->store('public/images/ayantdroit/certificat_vie');
                $ayantsdroits['certificat_vie'] = basename($path3);
            }
            // $file = $request->file('cni');
            // $file = $request->file->store('public/images');
            // $new_name = rand() . '.' . $file->getClientOriginalExtension();
            // $file->move(public_path('images'), $new_name);

            $ayantsdroits->save();
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
            $ayantsdroits = AyantDroit::find($id);
            $user = User::select('nom', 'matricule', 'prenom', 'email')->where('id', $ayantsdroits->users_id)->first();
            return view('pages.addayantsdroits', ['father' => $user, 'id' => $ayantsdroits->users_id, 'ayantsdroits' => $ayantsdroits]);
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
        $validator = FacadesValidator::make($request->all(), [
            'id' => ['required', 'exists:users,id'],
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'statut' => ['required', 'string', 'max:50'],
            // 'cni' => ['nullable', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
            // 'acte_naissance' => ['nullable', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
            // 'certificat_vie' => ['nullable', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $ayantsdroits  = AyantDroit::find($request->ids);
            $ayantsdroits['nom'] = $request['nom'];
            $ayantsdroits['prenom'] = $request['prenom'];
            $ayantsdroits['statut'] = $request['statut'];
            $ayantsdroits['users_id'] = $request['id'];
            $expathcni = $ayantsdroits['cni'];
            $expathacte_naissance = $ayantsdroits['acte_naissance'];
            $expathcertificat_vie = $ayantsdroits['certificat_vie'];
            if ($request->hasFile('cni') && $request->file('cni')->isValid()) {
                $path1 = $request->file('cni')->store('public/images/ayantdroit/cni');
                $ayantsdroits['cni'] = basename($path1);
            }
            if ($request->hasFile('acte_naissance') && $request->file('acte_naissance')->isValid()) {
                $path2 = $request->file('acte_naissance')->store('public/images/ayantdroit/acte_naissance');
                $ayantsdroits['acte_naissance'] = basename($path2);
            }
            if ($request->hasFile('certificat_vie') && $request->file('certificat_vie')->isValid()) {
                $path3 = $request->file('certificat_vie')->store('public/images/ayantdroit/certificat_vie');
                $ayantsdroits['certificat_vie'] = basename($path3);
            }

            $ayantsdroits->save();
            DB::commit();

            if ($request->hasFile('cni') && $request->file('cni')->isValid()) {
                if (Storage::exists('public/images/ayantdroit/cni/' . $expathcni)) {
                    Storage::delete('public/images/ayantdroit/cni/' . $expathcni);
                }
            }
            if ($request->hasFile('acte_naissance') && $request->file('acte_naissance')->isValid()) {
                if (Storage::exists('public/images/ayantdroit/acte_naissance/' . $expathacte_naissance)) {
                    Storage::delete('public/images/ayantdroit/acte_naissance/' . $expathacte_naissance);
                }
            }
            if ($request->hasFile('certificat_vie') && $request->file('certificat_vie')->isValid()) {
                if (Storage::exists('public/images/ayantdroit/certificat_vie/' . $expathcertificat_vie)) {
                    Storage::delete('public/images/ayantdroit/certificat_vie/' . $expathcertificat_vie);
                }
            }

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
    public function deleteayantdroits(Request $request)
    {
        try {
            $ayantsdroits = AyantDroit::find($request->id);
            $expathcni = $ayantsdroits['cni'];
            $expathacte_naissance = $ayantsdroits['acte_naissance'];
            $expathcertificat_vie = $ayantsdroits['certificat_vie'];
            $ayantsdroits->delete();
            if ($request->hasFile('cni') && $request->file('cni')->isValid()) {
                if (Storage::exists('public/images/ayantdroit/cni/' . $expathcni)) {
                    Storage::delete('public/images/ayantdroit/cni/' . $expathcni);
                }
            }
            if ($request->hasFile('acte_naissance') && $request->file('acte_naissance')->isValid()) {
                if (Storage::exists('public/images/ayantdroit/acte_naissance/' . $expathacte_naissance)) {
                    Storage::delete('public/images/ayantdroit/acte_naissance/' . $expathacte_naissance);
                }
            }
            if ($request->hasFile('certificat_vie') && $request->file('certificat_vie')->isValid()) {
                if (Storage::exists('public/images/ayantdroit/certificat_vie/' . $expathcertificat_vie)) {
                    Storage::delete('public/images/ayantdroit/certificat_vie/' . $expathcertificat_vie);
                }
            }
            return response()->json(["success" => "Suppression éffectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
}