<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\Emprunt;
use App\Models\Notification;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/* C'est un contrôleur qui contient les méthodes qui servent à gérer l'emprunt */

class EmpruntController extends Controller
{

    /**
     * Il renvoie la vue de la page contracteur_emprunt.
     * 
     * @return The view 'pages.contracter_emprunt'
     */
    public function appelASouscriptionIndex()
    {
        return view('pages.contracter_emprunt');
    }

    /**
     * Il renvoie une vue appelée upload_lettre_de_motivation
     * 
     * @return Une vue
     */
    public function showFormUploadLettreDeMotivation(Request $request)
    {
        $emprunt = Emprunt::find((int)$request->id);
        if ($emprunt->etat == 'Dossier en etude') {
            abort(404);
        }
        return view('pages.upload_lettre_de_motivation', ['id' => $request->id]);
    }


    /**
     * Il renvoie une vue appelée lettre_souscription
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Une vue
     */
    public function downloadLettreSouscription(Request $request)
    {
        $emprunt = Emprunt::with('membre')->find((int)$request->id);
        return view('pages.impressions.lettre_souscription', ['emprunt' => $emprunt]);
    }

    /**
     * Il crée un emprunt
     * 
     * @param Request request L'objet de la requête.
     */
    public function createEmprunt(Request $request)
    {
        try {
            $validator = FacadesValidator::make($request->all(), [
                // 'type_emprunt' => ['required', 'string', 'max:255'],
                'objet' => ['required', 'string'],
                'montant' => ['required', 'numeric'],
            ]);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            if ($request->type_emprunt == 'BLI') {
                if ($request->montant < 2000000 || $request->montant > 10000000) {
                    return response()
                        ->json(['error' => 'Le montant de prêt du Bridge Loan Immo est situé 2 000 000 et 10 000 000 de FCFA.']);
                }
            } else if ($request->type_emprunt == 'BBL') {
                if ($request->montant < 500000 || $request->montant > 3000000) {
                    return response()
                        ->json(['error' => 'Le montant de prêt du Bridge Loan Immo est situé 500 000 et 3 000 000 de FCFA.']);
                }
            }
            DB::beginTransaction();
            $emprunt  = new Emprunt();
            $emprunt['type'] = $request['type_emprunt'];
            $emprunt['date'] = Carbon::now();
            $emprunt['code'] = uniqid();
            $emprunt['montant'] = $request['montant'];
            $emprunt['objet'] = $request['objet'];
            $emprunt['etat'] = 'En attente de LS signé'; //LS = Lettre de Souscription 
            $emprunt['users_id'] = Auth::user()->id;
            $emprunt->save();
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !", "id" => $emprunt->id, "route" => route('emprunt.showFormUploadLettreDeMotivation', $emprunt->id)]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
            // return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Il renvoie une vue appelée 'pages.liste_emprunt'
     * 
     * @param Request request L'objet de la requête.
     */
    public function viewForListOfEmpruntOfUUserWhoIsConnect(Request $request)
    {
        return view('pages.liste_emprunt');
    }

    /**
     * Il renvoie grace à une requette Ajax la list des emprunts de l'utilisateur actuellement connecter
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Un objet JSON contenant les données à utiliser par DataTables.
     */
    public function getListOfEmpruntOfUUserWhoIsConnectAjax(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('users_id', '=', Auth::user()->id);
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })
                ->addColumn("status", function ($data) {
                    return $data->etat == 'En attente de LS signé' ? "<span class='badge badge-outline-warning'>En attente de Lettre de souscription signé</span>" : ($data->etat == 'Dossier en etude' ?  "<span class='badge badge-outline-info'>Dossier en cour d'étude</span>" : ($data->etat == 'Dossier accepter' ?  "<span class='badge badge-outline-success'>Dossier accepter</span>" : "<span class='badge badge-outline-danger'>Dossier refuser</span>"));
                })
                ->addColumn('Actions', function ($data) {
                    return $data->etat == 'En attente de LS signé' ? '<ul class="nk-tb-actions gx-1">
               
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('emprunt.showFormUploadLettreDeMotivation', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Continuer le dossier">
                       <em class="icon ni ni-forward-ios"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                
                                <li><a href="' . route('emprunt.showFormUploadLettreDeMotivation', $data->id) . '"><em class="icon ni ni-forward-ios"></em><span>Continuer le dossier</span></a></li>
                                
                                 
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>' : ($data->etat == 'Dossier accepter' ? '<ul class="nk-tb-actions gx-1">
               
                  <li class="nk-tb-action-hidden">
                    <a href="' . route('emprunt.download-lettre-adjudication', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Télécharger la lettre d\'adjudication">
                       <em class="icon ni ni-download"></em>
                    </a>
                </li>
                 <li class="nk-tb-action-hidden">
                    <a href="' . route('emprunt.download-lettre-engagement', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Télécharger la lettre d\'engagement">
                       <em class="icon ni ni-download"></em>
                    </a>
                </li>
                 <li class="nk-tb-action-hidden">
                    <a href="' . route('emprunt.download-ordre_paiement', $data->id) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Télécharger l\'ordre de paiement">
                       <em class="icon ni ni-download"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                
                                <li><a href="' . route('emprunt.download-lettre-adjudication', $data->id) . '"><em class="icon ni ni-download"></em><span>Télécharger la lettre d\'adjudication</span></a></li>
                                <li><a href="' . route('emprunt.download-lettre-engagement', $data->id) . '"><em class="icon ni ni-download"></em><span>Télécharger la lettre d\'engagement</span></a></li>
                                <li><a href="' . route('emprunt.download-ordre_paiement', $data->id) . '"><em class="icon ni ni-download"></em><span>Télécharger l\'ordre de paiement</span></a></li>
                                
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>' : '');
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Cette fonction affiche le formulaire qui affiche la liste des emprunts validés par la mutuelle
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Une Vue
     */
    public function showFormWhoShowListOfEmpruntWhoIsValidateByTheMutual()
    {
        return view('pages.liste_emprunt_valider_par_la_mutuelle');
    }

    /**
     * Une fonction qui permet d'obtenir la liste des emprunts validés par la mutuelle.
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Une liste d'emprunts validés par la mutuelle
     */
    public function getListOfEmpruntWhoIsValidateByTheMutualAjax(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', '=', "Dossier accepter");
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("date_fin", function ($data) {
                    return $data->date_de_fin;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })
                ->addColumn("status", function ($data) {
                    return "<span class='badge badge-outline-success'>Dossier accepter</span>";
                })
                ->addColumn('Actions', function ($data) {
                    return  '<ul class="nk-tb-actions gx-1">
               
                  <li class="nk-tb-action-hidden">
                    <a href="#" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Rembouser l\'emprunt">
                    <em class="icon ni ni-forward-ios"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                                
                                <li><a href="#"><em class="icon ni ni-forward-ios"></em><span>Rembouser l\'emprunt</span></a></li>
                                
                                 
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Cette fonction affiche le formulaire qui affiche la liste des emprunts rembouser par le membre
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Une Vue
     */
    public function showFormWhoShowListOfEmpruntWhoIsIsReturnByTheMember()
    {
        return view('pages.liste_emprunt_rembourser_par_les_membres');
    }

    /**
     * Une fonction qui permet d'obtenir la liste des emprunts rembourser par le membre.
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Une liste d'emprunts validés par la mutuelle
     */
    public function getListOfEmpruntWhoIsReturnByTheMemberAjax(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', '=', "Dossier rembourser");
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("date_fin", function ($data) {
                    return $data->date_de_fin;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })
                ->addColumn("status", function ($data) {
                    return "<span class='badge badge-outline-success'>Dossier rembourser</span>";
                })
                ->setRowClass("nk-tb-item")
                ->rawColumns(['membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Elle upload la lettre de motivation pour completer le dossier
     * 
     * @param Request request L'objet de la requête.
     */
    public function uploadLettreDeSouscripption(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'id' => ['required', 'numeric'],
            'lss' => ['required'],
            'adi' => ['required'],
            'bds' => ['required'],
            'ddt' => ['required'],
            'pdv' => ['required'],
            'cdt' => ['required'],
            'autres' => [],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $emprunt  = Emprunt::find($request->id);
            $emprunt['etat'] = "Dossier en etude";
            if ($request->hasFile('lss') && $request->file('lss')->isValid()) {
                $path1 = $request->file('lss')->store('public/images/upload');
                $emprunt['link_lettre_souscription'] = basename($path1);
            }
            if ($request->hasFile('adi') && $request->file('adi')->isValid()) {
                $path1 = $request->file('adi')->store('public/images/upload');
                $emprunt['link_avis_imposition'] = basename($path1);
            }
            if ($request->hasFile('bds') && $request->file('bds')->isValid()) {
                $path1 = $request->file('bds')->store('public/images/upload');
                $emprunt['link_bulletin_salaire'] = basename($path1);
            }
            if ($request->hasFile('ddt') && $request->file('ddt')->isValid()) {
                $path1 = $request->file('ddt')->store('public/images/upload');
                $emprunt['link_devis_travaux'] = basename($path1);
            }
            if ($request->hasFile('pdv') && $request->file('pdv')->isValid()) {
                $path1 = $request->file('pdv')->store('public/images/upload');
                $emprunt['link_proposition_vente'] = basename($path1);
            }
            if ($request->hasFile('cdt') && $request->file('cdt')->isValid()) {
                $path1 = $request->file('cdt')->store('public/images/upload');
                $emprunt['link_contrat_travail'] = basename($path1);
            }
            if ($request->hasFile('autres') && $request->file('autres')->isValid()) {
                $path1 = $request->file('autres')->store('public/images/upload');
                $emprunt['link_autres'] = basename($path1);
            }
            $emprunt->save();
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
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function getViewListEmpruntWhoWatingTheValidationByAdmin()
    {
        return view('pages.liste_emprunt_a_valider');
    }

    /**
     * Il renvoie grace à une requette Ajax la list des emprunts de l'utilisateur actuellement connecter
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Un objet JSON contenant les données à utiliser par DataTables.
     */
    public function getListEmpruntWhoWatingTheValidationByAdminAjax(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', 'Dossier en etude');
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("objet", function ($data) {
                    return $data->objet;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })
                ->addColumn("status", function ($data) {
                    return $data->etat == 'Dossier en etude' ?  "<span class='badge badge-outline-info'>Dossier en cour d'étude</span>" : '';
                })
                ->addColumn('Actions', function ($data) {
                    return $data->etat == 'Dossier en etude' ? '<ul class="nk-tb-actions gx-1">
                    
                 <li class="nk-tb-action-hidden">
                    <a href="' . asset('upload/' . $data->link_lettre_souscription) . '" class="btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Télécharger la lettre de souscription">
                        <em class="icon ni ni-download"></em>
                    </a>
                </li>
                  <li class="nk-tb-action-hidden">
                    <a  data_id="' . $data->id . '" class="valide-data-emprunt btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Valider le dossier">
                      <em class="icon ni ni-check"></em>
                    </a>
                </li>
                <li class="nk-tb-action-hidden">
                    <a data_id="' . $data->id . '" class="refuse-data-emprunt btn btn-trigger btn-icon" data-toggle="tooltip" data-placement="top" title="Refuser le dossier">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </li>
                <li>
                    <div class="drodown">
                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <ul class="link-list-opt no-bdr">
                            
                                <li><a href="' . asset('upload/' . $data->link_lettre_souscription) . '" > <em class="icon ni ni-download"></em><span>Télécharger la lettre de souscription</span></a></li>
                                
                                <li><a class="valide-data-emprunt" data_id="' . $data->id . '"><em class="icon ni ni-check"></em><span>Valider le dossier</span></a></li>
                                
                                <li><a class="refuse-data-emprunt" data_id="' . $data->id . '"> <em class="icon ni ni-cross"></em><span>Refuser le dossier</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>' : '';
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function donwloadMultipleFileEmprunt(Request $request)
    {
        $zip = new ZipArchive;
        $data = Emprunt::where('id', $request->id)->first();
        if ($zip->open('dossier.zip', ZipArchive::CREATE)) {
            $zip->addFromString(asset('upload/' . $data->link_lettre_souscription), $data->link_lettre_souscription);
            //     $zip->addFile(asset('upload/' . $data->link_avis_imposition), $data->link_avis_imposition);
            //     $zip->addFile(asset('upload/' . $data->link_bulletin_salaire), $data->link_bulletin_salaire);
            //     $zip->addFile(asset('upload/' . $data->link_devis_travaux), $data->link_devis_travaux);
            //     $zip->addFile(asset('upload/' . $data->link_proposition_vente), $data->link_proposition_vente);
            //     $zip->addFile(asset('upload/' . $data->link_contrat_travail), $data->link_contrat_travail);
            //     $zip->addFile(asset('upload/' . $data->link_autres), $data->link_autres);
        }
        $zip->close();
        return response()->download(asset('upload/' . $data->link_lettre_souscription));
        return response()->download([asset('upload/' . $data->link_lettre_souscription), asset('upload/' . $data->link_avis_imposition), asset('upload/' . $data->link_bulletin_salaire), asset('upload/' . $data->link_devis_travaux), asset('upload/' . $data->link_proposition_vente), asset('upload/' . $data->link_contrat_travail), asset('upload/' . $data->link_autres),]);
    }

    /**
     * > Cette fonction permet d'accepter le dossier
     * 
     * @param Request requuest L'objet de la requête.
     */
    public function accepterLeDossier(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'id' => ['required', 'numeric'],
            'montant' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $emprunt  = Emprunt::find($request->id);
            $montantCaissePrincipal = Caisse::select('principal')->first();
            if ($montantCaissePrincipal->principal < $emprunt->montant) {
                return response()->json(["error" => "Le montant de l'emprunt est superieur au montant de la caisse actuel."]);
            }
            $emprunt['etat'] = "Dossier accepter";
            $emprunt['date_de_validation'] = Carbon::now();
            $emprunt['date_de_fin'] = Carbon::now()->addMonths(6);
            $emprunt['montant_commission'] = $request->montant;
            $emprunt->save();

            $caisse = Caisse::first();
            $caisse->emprunt = $caisse->emprunt - $emprunt->montant;
            $caisse->save();

            Notification::where('type', '=', 'Dossier emprunt en etude')->where('etat', 'Non lue')->update(['etat' => 'Lue']);
            DB::commit();
            return response()->json(["success" => "Enregistrement éffectuer !", "id" => $emprunt->id, "route" => route('emprunt.showFormUploadLettreDeMotivation', $emprunt->id)]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * > Cette fonction permet de refuser un dossier
     * 
     * @param Request requuest L'objet de la requête.
     */
    public function refuserLeDossier(Request $request)
    {
        $validator = FacadesValidator::make($request->all(), [
            'id' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return response()
                ->json(['errors' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $emprunt  = Emprunt::find($request->id);
            $emprunt['etat'] = "Dossier refuser";
            $emprunt->save();
            DB::commit();
            return response()->json(["success" => "Enregistrement éffectuer !", "id" => $emprunt->id, "route" => route('emprunt.showFormUploadLettreDeMotivation', $emprunt->id)]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Il renvoie une vue appelée lettre_adjudication
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Une vue
     */
    public function downloadLettreAjudication(Request $request)
    {
        $emprunt = Emprunt::with('membre')->find((int)$request->id);
        return view('pages.impressions.lettre_adjudication', ['emprunt' => $emprunt]);
    }

    /**
     * Il renvoie une vue appelée lettre_engagement
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Une vue
     */
    public function downloadLettreEngagement(Request $request)
    {
        $emprunt = Emprunt::with('membre')->find((int)$request->id);
        return view('pages.impressions.lettre_engagement', ['emprunt' => $emprunt]);
    }

    /**
     * Il renvoie une vue appelée ordre_paiement
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Une vue
     */
    public function downloadOrdrePaiement(Request $request)
    {
        $emprunt = Emprunt::with('membre')->find((int)$request->id);
        return view('pages.impressions.ordre_paiement', ['emprunt' => $emprunt]);
    }

    public function getViewEnregistrementManuelD1Emprunt()
    {
        return view('pages.ajout_manuel_emprunt');
    }

    /**
     * Je veux retourner une réponse json si la condition est vraie, sinon je veux continuer
     * l'exécution de la fonction
     * 
     * @param request L'objet de la requête.
     */
    public function saveEmpruntManuel(Request $request)
    {
        try {
            $attributeNames = array(
                'type_emprunt' => 'type de l\'emprunt',
                'montant_commission' => 'montant de la commission',
                'id' => 'membre',
            );
            $validator = FacadesValidator::make($request->all(), [
                'id' => ['required', 'integer'],
                'type_emprunt' => ['required', 'string', 'max:255'],
                'objet' => ['required', 'string'],
                'montant' => ['required', 'numeric'],
                'montant_commission' => ['numeric'],
            ]);
            $validator->setAttributeNames($attributeNames);
            if ($validator->fails()) {
                return response()
                    ->json(['errors' => $validator->errors()->all()]);
            }
            if ($request->type_emprunt == 'BLI') {
                if ($request->montant < 2000000 || $request->montant > 10000000) {
                    return response()
                        ->json(['error' => 'Le montant de prêt du Bridge Loan Immo est situé 2 000 000 et 10 000 000 de FCFA.']);
                }
            } else if ($request->type_emprunt == 'BBL') {
                if ($request->montant < 500000 || $request->montant > 3000000) {
                    return response()
                        ->json(['error' => 'Le montant de prêt du Bridge Loan Immo est situé 500 000 et 3 000 000 de FCFA.']);
                }
            }
            DB::beginTransaction();
            $emprunt  = new Emprunt();
            $emprunt['type'] = $request['type_emprunt'];
            $emprunt['date'] = Carbon::now();
            $emprunt['date_de_fin'] = Carbon::now();
            $emprunt['code'] = uniqid();
            $emprunt['montant'] = $request['montant'];
            $emprunt['montant_commission'] = $request['montant_commission'];
            $emprunt['objet'] = $request['objet'];
            $emprunt['etat'] = 'Dossier rembourser';
            $emprunt['users_id'] = $request['id'];
            $emprunt->save();
            DB::commit();

            return response()->json(["success" => "Enregistrement éffectuer !", "id" => $emprunt->id, "route" => route('emprunt.showFormUploadLettreDeMotivation', $emprunt->id)]);
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
            // return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Il renvoie la vue de la page affichant la liste des emprunts.
     * 
     * @return The view 'pages.liste_emprunt_expirés'
     */
    public function getViewForListOfEmpruntWhoIsExpire()
    {
        return view('pages.liste_emprunt_expirés');
    }

    /**
     * Il renvoie grace à une requette Ajax la list des emprunts expirés
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Un objet JSON contenant les données à utiliser par DataTables.
     */
    public function getListOfEmpruntWhoIsExpireAjax(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('date_de_fin', '>', Carbon::now());
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("date_de_fin", function ($data) {
                    return $data->date_de_fin;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })
                ->addColumn("status", function ($data) {
                    return "<span class='badge badge-outline-danger'>Delais dépassé</span>";
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function historiqueEmprunts()
    {
        $BLtotal = Emprunt::where('etat', '=', 'Dossier accepter')->where('type', 'BL')->sum('montant');
        $BLItotal = Emprunt::where('etat', '=', 'Dossier accepter')->where('type', 'BLI')->sum('montant');
        $BBLtotal = Emprunt::where('etat', '=', 'Dossier accepter')->where('type', 'BBL')->sum('montant');
        $ASStotal = Emprunt::where('etat', '=', 'Dossier accepter')->where('type', 'ASS')->sum('montant');
        $ASGtotal = Emprunt::where('etat', '=', 'Dossier accepter')->where('type', 'ASG')->sum('montant');
        return view('pages.historique_emprunts', compact('BLtotal', 'BLItotal', 'BBLtotal', 'ASStotal', 'ASGtotal'));
    }

    /**
     * Il renvoie grace à une requette Ajax la list des emprunts expirés
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Un objet JSON contenant les données à utiliser par DataTables.
     */
    public function getHistoriqueBLEmprunt(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'BL')->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("date_de_fin", function ($data) {
                    return $data->date_de_fin;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Il renvoie grace à une requette Ajax la list des emprunts expirés
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Un objet JSON contenant les données à utiliser par DataTables.
     */
    public function getHistoriqueBLIEmprunt(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'BLI')->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("date_de_fin", function ($data) {
                    return $data->date_de_fin;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Il renvoie grace à une requette Ajax la list des emprunts expirés
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Un objet JSON contenant les données à utiliser par DataTables.
     */
    public function getHistoriqueBBLEmprunt(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'BBL')->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("date_de_fin", function ($data) {
                    return $data->date_de_fin;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Il renvoie grace à une requette Ajax la list des emprunts expirés
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Un objet JSON contenant les données à utiliser par DataTables.
     */
    public function getHistoriqueASSEmprunt(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'ASS')->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("date_de_fin", function ($data) {
                    return $data->date_de_fin;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    /**
     * Il renvoie grace à une requette Ajax la list des emprunts expirés
     * 
     * @param Request request L'objet de la requête.
     * 
     * @return Un objet JSON contenant les données à utiliser par DataTables.
     */
    public function getHistoriqueASGEmprunt(Request $request)
    {
        try {

            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'ASG')->get();
            return \Yajra\DataTables\DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("id", function ($data) {
                    return $data->id;
                })
                ->addColumn("updated_at", function ($data) {
                    return $data->created_at;
                })
                ->addColumn("date", function ($data) {
                    return $data->date;
                })
                ->addColumn("date_de_fin", function ($data) {
                    return $data->date_de_fin;
                })
                ->addColumn("membre", function ($data) {
                    return
                        "<div class='user-card'>
                <div class='user-avatar bg-dim-primary d-none d-sm-flex'>
                    <span>EMP</span>
                </div>
                <div class='user-info'>
                    <span class='tb-lead'>" . $data->membre->nom . ' ' . $data->membre->prenom . "</span>
                </div>
            </div>";
                })
                ->editColumn("type", function ($data) {
                    return ($data->type == 'BLI' ? 'Bridge Loan Immo' : ($data->type == 'BBL' ? 'Back to Back Loan' : ($data->type == 'ASS' ? 'Avance Sur Salaire' : ($data->type == 'BL' ? 'Bridge Loan' : ($data->type == 'ASG' ? 'Avance Sur Gratification' : '')))));
                })
                ->addColumn("montant", function ($data) {
                    return number_format($data->montant, 0, ',', ' ');
                })->setRowClass("nk-tb-item")
                ->rawColumns(['Actions', 'membre', 'status'])
                ->make(true);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDeMesEmprunts(Request $request)
    {
        try {
            $data = Emprunt::with('membre')->where('users_id', '=', Auth::user()->id)->get();
            return view('pages.impressions.liste_de_mmes_emprunts', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDesEmpruntsEnCourEtude(Request $request)
    {
        try {
            $data = Emprunt::with('membre')->where('etat', 'Dossier en etude')->get();
            return view('pages.impressions.liste_des emprunts_en_cour_etude', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDesEmpruntsValiderParLaMutuelle(Request $request)
    {
        try {
            $data = Emprunt::with('membre')->where('etat', '=', "Dossier accepter")->get();
            return view('pages.impressions.liste_des emprunts_valider_par_la_mutuelle', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDesEmpruntsExpires(Request $request)
    {
        try {
            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('date_de_fin', '>', Carbon::now())->get();
            return view('pages.impressions.liste_des emprunts_expirer', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDesEmpruntsBL(Request $request)
    {
        try {
            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'BL')->get();
            return view('pages.impressions.liste_des emprunts_bl', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDesEmpruntsBLI(Request $request)
    {
        try {
            $data = Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'BLI')->get();
            return view('pages.impressions.liste_des emprunts_bli', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDesEmpruntsBBL(Request $request)
    {
        try {
            $data =
                Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'BBL')->get();
            return view('pages.impressions.liste_des emprunts_bbl', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDesEmpruntsASS(Request $request)
    {
        try {
            $data =
                Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'ASS')->get();
            return view('pages.impressions.liste_des emprunts_ass', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }

    public function impressionListDesEmpruntsASG(Request $request)
    {
        try {
            $data =
                Emprunt::with('membre')->where('etat', '=', 'Dossier accepter')->where('type', 'ASG')->get();
            return view('pages.impressions.liste_des emprunts_asg', ['datas' => $data]);
        } catch (Exception $e) {
            return response()->json(["error" => "Une erreur s'est produite."]);
        }
    }
}
