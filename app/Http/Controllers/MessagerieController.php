<?php

namespace App\Http\Controllers;

use App\Models\Messagerie;
use App\Models\Notification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MessagerieController extends Controller
{
    public function sendMessageToMutual()
    {
        return view('pages.send_message_to_mutual');
    }

    public function SendMessageToMutualPost(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'fichier' => ['required'],
            'message' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $message  = new Messagerie();
            $message['etat'] = "Non lu";
            $message['expediteur'] = "Membre";
            $message['date'] = Carbon::now();
            $message['description'] = $request->message;
            if ($request->hasFile('fichier') && $request->file('fichier')->isValid()) {
                $path1 = $request->file('fichier')->store('public/images/message');
                $message['link_file'] = basename($path1);
            }
            $message['users_id'] = Auth::user()->id;
            $message->save();

            $notification = new Notification();
            $notification->type = 'Dossier emprunt en etude';
            $notification->date = Carbon::now();
            $notification->etat = "Non lue";
            $notification->route_name = 'messagerie.getMessageMember';
            $notification->destinataire = null;
            $notification->save();
            DB::commit();
            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function sendMessageToMember()
    {
        return view('pages.send_message_to_member');
    }


    public function SendMessageToMemberPost(Request $request)
    {
        $attributeNames = array(
            'id_membre' => 'membre',
        );
        $messages = [
            'id_membre.numeric' => 'Veuillez selectionner un membre',
        ];
        $validator = FacadesValidator::make($request->all(), [
            'id_membre' => ['required', 'numeric'],
            'fichier' => ['required'],
            'message' => ['required', 'string'],
        ], $messages);
        $validator->setAttributeNames($attributeNames);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $message  = new Messagerie();
            $message['etat'] = "Non lu";
            $message['expediteur'] = "Mutuelle";
            $message['date'] = Carbon::now();
            $message['description'] = $request->message;
            if ($request->hasFile('fichier') && $request->file('fichier')->isValid()) {
                $path1 = $request->file('fichier')->store('public/images/message');
                $message['link_file'] = basename($path1);
            }
            $message['users_id'] = $request->id_membre;
            $message->save();

            $notification = new Notification();
            $notification->type = 'Dossier emprunt en etude';
            $notification->date = Carbon::now();
            $notification->etat = "Non lue";
            $notification->route_name = 'emprunt.viewListEmpruntWhoWatingTheValidationByAdmin';
            $notification->destinataire = null;
            $notification->save();
            DB::commit();
            return response()->json(["success" => "Enregistrement éffectuer !"]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function getMessageMember()
    {
        return view('pages.message_membre');
    }

    public function getMessageMemberAjax(Request $request)
    {
        try {

            $data = Messagerie::with('membre')->where('expediteur', '=', 'Mutuelle')->where('users_id', '=', Auth::user()->id);
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>MEM</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>Mutuelle</span>
                </div>
            </div>";
                })
                ->addColumn("date", function ($data) {
                    return Carbon::parse($data->date)->format('d-m-Y');
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('messagerie.detailMessage', $data->id) . '"  class="btn btn-trigger btn-detail-message" data-toggle="tooltip" data-placement="top" title="Lire le message">
                       <em class="icon ni ni-forward-ios"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="' . route('messagerie.detailMessage', $data->id) . '" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="#" ><em class="icon ni ni-forward-ios"></em><span>Lire le message</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })
                ->addColumn("status", function ($data) {
                    return $data->etat == false ? "<span class='badge badge-outline-warning'>Non Lu</span>" : "<span class='badge badge-outline-success'>Lu</span>";
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function getMessageMutual()
    {
        return view('pages.message_mutuelle');
    }

    public function getMessageMutualAjax(Request $request)
    {
        try {

            $data = Messagerie::with('membre')->where('expediteur', '=', 'Membre');
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>MEM</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . " " . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->addColumn("date", function ($data) {
                    return Carbon::parse($data->date)->format('d-m-Y');
                })
                ->addColumn('Actions', function ($data) {
                    return '<ul class="nk-tb-actions gx-1">
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('messagerie.detailMessageMutual', $data->id) . '"  class="btn btn-trigger btn-detail-message" data-toggle="tooltip" data-placement="top" title="Lire le message">
                       <em class="icon ni ni-forward-ios"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="' . route('messagerie.detailMessageMutual', $data->id) . '" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                <li><a href="#" ><em class="icon ni ni-forward-ios"></em><span>Lire le message</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })
                ->addColumn("status", function ($data) {
                    return $data->etat == false ? "<span class='badge badge-outline-warning'>Non Lu</span>" : "<span class='badge badge-outline-success'>Lu</span>";
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function detailMessageMembre(Request $request)
    {
        $data = Messagerie::with('membre')->where('expediteur', '=', 'Mutuelle')->where('users_id', '=', Auth::user()->id)->where('id', '=', $request->id)->first();
        $data->etat = true;
        $data->save();
        return view('pages.detail_message', ['data' => $data]);
    }

    public function detailMessageMutual(Request $request)
    {
        $data = Messagerie::with('membre')->where('expediteur', '=', 'Membre')->where('id', '=', $request->id)->first();
        $data->etat = true;
        $data->save();
        return view('pages.detail_message', ['data' => $data]);
    }

    public function downloadFile(Request $request)
    {
        return Storage::download('public/images/message/' . $request->name);
    }
}
