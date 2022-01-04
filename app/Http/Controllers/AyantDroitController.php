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
        //
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
        //
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