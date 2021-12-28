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
            'cni' => ['max:2000', 'mimes:jpeg,png,doc,docs,pdf'],
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
            $file = $request->file('cni');
            $file = $request->file->store('public/images');
            // $new_name = rand() . '.' . $file->getClientOriginalExtension();
            // $file->move(public_path('images/upload'), $new_name);
            $ayantsdroits['cni'] = $file;

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