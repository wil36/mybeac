<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CotisationController extends Controller
{

    public function cotisation()
    {
        return view('pages.cotisation');
    }

    public function getUserCotisation(Request $request)
    {
        $data = User::with('category')->latest()->get();
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
                     <img class='object-cover w-8 h-8 rounded-full'
                                                            src='$data->profile_photo_url'
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
                return $data->categories_id;
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
            ->rawColumns(['matricule', 'select', 'status'])
            ->make(true);
    }
}