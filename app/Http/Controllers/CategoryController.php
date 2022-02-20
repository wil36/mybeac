<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.categories');
    }

    public function getcategoriesList(Request $request)
    {
        try {
            $data = Category::latest()->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->editColumn("code", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>CAT</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>$data->code</span>
                </div>
            </div>";;
                })
                ->addColumn("libelle", function ($data) {
                    return $data->libelle;
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
               
                <li class="nk-tb-action-hidden">
                    <a href="' . route('categories.edit', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Modifier">
                        <em class="icon ni ni-edit"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a href="" data_id="' . $data->id . '" class="btn btn-trigger btn-icon delete-data-cat" data-toggle="tooltip" data-placement="top" title="Supprimer">
                       <em class="icon ni ni-trash"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="' . route('categories.edit', $data->id) . '" > <em class="icon ni ni-edit"></em><span>Modifier</span></a></li>
                                 
                                <li><a href="" class="delete-data-cat" data_id="' . $data->id . '"><em class="icon ni ni-trash"></em><span>Supprimer</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['code', 'Actions', 'status'])
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
    public function create()
    {
        return view('pages.addcategories');
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
            $validator = FacadesValidator::make($request->all(), [
                'code' => ['required', 'max:255', 'unique:categories,code'],
                'libelle' => ['required', 'string', 'max:255'],
                'montant' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            DB::beginTransaction();
            $categorie  = new Category();
            $categorie['code'] = $request['code'];
            $categorie['libelle'] = $request['libelle'];
            $categorie['montant'] = $request['montant'];
            $categorie->save();
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('pages.addcategories', ['categorie' => $category]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $categorie)
    {
        try {
            $validator = FacadesValidator::make($request->all(), [
                'code' => [
                    'required', 'max:255', Rule::unique('categories', 'code')->where(function ($query) use ($request) {
                        $query->where('id', '!=', $request['id']);
                    })
                ],
                'libelle' => ['required', 'string', 'max:255'],
                'montant' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            DB::beginTransaction();
            $categorie  = Category::find($request['id']);
            $categorie['code'] = $request['code'];
            $categorie['libelle'] = $request['libelle'];
            $categorie['montant'] = $request['montant'];
            $categorie->save();
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\categorie  $categorie
     * @return \Illuminate\Http\Response
     */
    public function deletecategory(Request $request)
    {
        try {
            Category::find($request->id)->delete();
            return response()->json(["success" => "Suppression éffectuer avec succès"]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function getCategories(Request $request)
    {
        try {
            $data = Category::select("id", "libelle", "code")->where(function ($query) use ($request) {
                $query->where('code', 'like', '%' . $request->search . '%');
            })->orWhere(function ($query) use ($request) {
                $query->where(
                    'libelle',
                    'like',
                    '%' . $request->search . '%'
                );
            })->limit(5)->get();
            $resp = array();
            foreach ($data as $e) {
                $resp[] = array('id' => $e->id, 'text' => $e->code . "   |  " . $e->libelle, 'value' => $e->id);
            }
            return response()->json($resp);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
}