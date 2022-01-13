<?php

namespace App\Http\Controllers;

use App\Models\AyantDroit;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = AyantDroit::select("id", "nom", "prenom", "statut")->where(function ($query) use ($request) {
            $query->where('nom', 'like', '%' . $request->search . '%')->where('users_id', $request->id);
        })->orWhere(function ($query) use ($request) {
            $query->where('prenom', 'like', '%' . $request->search . '%')->where('users_id', $request->id);
        })
            ->limit(5)->get();
        $resp = array();
        foreach ($data as $e) {
            $resp[] = array('id' => $e->id, 'text' => $e->nom . ' ' . $e->prenom . ' | ' . $e->statut, 'value' => $e->id);
        }
        return response()->json($resp);
    }

    public function ayantsdroitsListForUser(Request $request)
    {
        $data = DB::select(DB::raw('select id, nom, prenom, created_at, statut from ayant_droits where users_id=' . $request->id));
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
            ->addColumn('Actions', function ($data) {
                return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                    <a href="' . route('ayantsdroits.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href="' . route('ayantsdroits.delete', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Supprimer">
                       <em class="icon ni ni-trash"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('ayantsdroits.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                 
                                <li><a href="' . route('ayantsdroits.delete', $data->id) . '" ><em class="icon ni ni-trash"></em><span>Supprimer</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
            })->setRowClass("nk-tb-item")
            ->rawColumns(['nom', 'Actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user = User::select('nom', 'matricule', 'prenom', 'email')->where('id', $id)->first();
        return view('pages.addayantsdroits', ['father' => $user, 'id' => $id]);
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
            'cni' => ['required', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
            'acte_naissance' => ['required', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
            'certificat_vie' => ['required', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $ayantsdroits  = new AyantDroit();
            $ayantsdroits['nom'] = $request['nom'];
            $ayantsdroits['prenom'] = $request['prenom'];
            $ayantsdroits['statut'] = $request['statut'];
            $ayantsdroits['users_id'] = $request['id'];
            if ($request->file('cni')) {
                // $name = time() . '' . uniqid() . '.' . $request()->file('cni')->getClientOriginalExtension();
                $path1 = $request->file('cni')->store('public/images/ayantdroit/cni');
                $ayantsdroits['cni'] = $path1;
                $path2 = $request->file('acte_naissance')->store('public/images/ayantdroit/acte_naissance');
                $ayantsdroits['acte_naissance'] = $path2;
                $path3 = $request->file('certificat_vie')->store('public/images/ayantdroit/certificat_vie');
                $ayantsdroits['certificat_vie'] = $path3;
            }
            // $file = $request->file('cni');
            // $file = $request->file->store('public/images');
            // $new_name = rand() . '.' . $file->getClientOriginalExtension();
            // $file->move(public_path('images'), $new_name);

            $ayantsdroits->save();
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
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
        $ayantsdroits = AyantDroit::find($id);
        $user = User::select('nom', 'matricule', 'prenom', 'email')->where('id', $ayantsdroits->users_id)->first();
        return view('pages.addayantsdroits', ['father' => $user, 'id' => $ayantsdroits->users_id, 'ayantsdroits' => $ayantsdroits]);
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
            // 'cni' => ['required', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
            // 'acte_naissance' => ['required', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
            // 'certificat_vie' => ['required', 'max:2048', 'file', 'mimes:jpeg,png,doc,docs,pdf'],
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
            // if ($request->file('cni')) {
            //     // $name = time() . '' . uniqid() . '.' . $request()->file('cni')->getClientOriginalExtension();
            //     $path1 = $request->file('cni')->store('public/images/ayantdroit/cni');
            //     $ayantsdroits['cni'] = $path1;
            //     $path2 = $request->file('acte_naissance')->store('public/images/ayantdroit/acte_naissance');
            //     $ayantsdroits['acte_naissance'] = $path2;
            //     $path3 = $request->file('certificat_vie')->store('public/images/ayantdroit/certificat_vie');
            //     $ayantsdroits['certificat_vie'] = $path3;
            // }
            // $file = $request->file('cni');
            // $file = $request->file->store('public/images');
            // $new_name = rand() . '.' . $file->getClientOriginalExtension();
            // $file->move(public_path('images'), $new_name);

            $ayantsdroits->save();
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}