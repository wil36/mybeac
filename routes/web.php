<?php

use App\Http\Controllers\{AcceuilController, AyantDroitController, CategoryController, CotisationController, PrestationController, TypePrestationController, UserController, CaisseController, DonsController, EmpruntController};
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', 'verified', 'agent'])->group(
    function () {

        //Accieul
        Route::get('/', [AcceuilController::class, 'index'])->name('dashboard');

        //cotisations
        Route::get('/getcotisationListForUser/{id}', [CotisationController::class, 'getcotisationListForUser'])->name('getcotisationListForUser');

        //prestations
        Route::get('/getprestationListForUser/{id}', [PrestationController::class, 'getprestationListForUser'])->name('getprestationListForUser');

        //ayant droits
        Route::get('ayantsdroitsListForUser/{id}', [AyantDroitController::class, 'ayantsdroitsListForUser'])->name('ayantsdroitsListForUser');
        Route::get('ayantsdroitsListForUserdecede/{id}', [AyantDroitController::class, 'ayantsdroitsListForUserdecede'])->name('ayantsdroitsListForUserdecede');

        //Route for emprunt
        Route::get('/ajouter-emprunt', [EmpruntController::class, 'appelASouscriptionIndex'])->name('emprunt.appelASouscription');
        Route::get('/ajouter-lettre-de-souscription/{id}', [EmpruntController::class, 'showFormUploadLettreDeMotivation'])->name('emprunt.showFormUploadLettreDeMotivation');
        Route::post('/save-emprunt', [EmpruntController::class, 'createEmprunt'])->name('emprunt.saveEmprunt');
        Route::get('/liste-de-mes-emprunt', [EmpruntController::class, 'viewForListOfEmpruntOfUUserWhoIsConnect'])->name('emprunt.viewForListOfEmpruntOfUUserWhoIsConnect');
        Route::get('/liste-de-mes-emprunt-ajax', [EmpruntController::class, 'getListOfEmpruntOfUUserWhoIsConnectAjax'])->name('emprunt.getListOfEmpruntOfUUserWhoIsConnectAjax');
        Route::get('/telecharger-lettre-souscription/{id}', [EmpruntController::class, 'downloadLettreSouscription'])->name('emprunt.download-lettre-souuscription');
        Route::get('/telecharger-lettre-adjudication/{id}', [EmpruntController::class, 'downloadLettreAjudication'])->name('emprunt.download-lettre-adjudication');
        Route::get('/telecharger-lettre-engagement/{id}', [EmpruntController::class, 'downloadLettreEngagement'])->name('emprunt.download-lettre-engagement');
        Route::get('/telecharger-ordre_paiement/{id}', [EmpruntController::class, 'downloadOrdrePaiement'])->name('emprunt.download-ordre_paiement');
        Route::post('/uploader-lettre-souscription', [EmpruntController::class, 'uploadLettreDeSouscripption'])->name('emprunt.uploader-lettre-souscription');
    }
);
// Route::middleware(['auth:sanctum', 'verified'])->get('/', [AcceuilController::class, 'index'])->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'admin'])->prefix('administration')->group(function () {


    //Route for emprunt
    Route::get('/liste-des-emprunts-a-valider', [EmpruntController::class, 'getViewListEmpruntWhoWatingTheValidationByAdmin'])->name('emprunt.viewListEmpruntWhoWatingTheValidationByAdmin');
    Route::get('/liste-des-emprunt-à-valider-ajax', [EmpruntController::class, 'getListEmpruntWhoWatingTheValidationByAdminAjax'])->name('emprunt.viewListEmpruntWhoWatingTheValidationByAdminAjax');
    Route::get('/liste-des-emprunts-valider-par-la-mutuelle', [EmpruntController::class, 'showFormWhoShowListOfEmpruntWhoIsValidateByTheMutual'])->name('emprunt.showFormWhoShowListOfEmpruntWhoIsValidateByTheMutual');
    Route::get('/liste-des-emprunts-valider-par-la-mutuelle-ajax', [EmpruntController::class, 'getListOfEmpruntWhoIsValidateByTheMutualAjax'])->name('emprunt.getListOfEmpruntWhoIsValidateByTheMutualAjax');
    Route::get('/liste-des-emprunts-rembourser-par-les-membres', [EmpruntController::class, 'showFormWhoShowListOfEmpruntWhoIsIsReturnByTheMember'])->name('emprunt.showFormWhoShowListOfEmpruntWhoIsIsReturnByTheMember');
    Route::get('/liste-des-emprunts-rembourser-par-les-membres-ajax', [EmpruntController::class, 'getListOfEmpruntWhoIsReturnByTheMemberAjax'])->name('emprunt.getListOfEmpruntWhoIsReturnByTheMemberAjax');
    Route::post('/acepter-le-dossier', [EmpruntController::class, 'accepterLeDossier'])->name('emprunt.accepterLeDossier');
    Route::post('/refuser-le-dossier', [EmpruntController::class, 'refuserLeDossier'])->name('emprunt.refuserLeDossier');
    Route::get('/ajouter-emprunt-manuel', [EmpruntController::class, 'getViewEnregistrementManuelD1Emprunt'])->name('emprunt.getViewEnregistrementManuelD1Emprunt');
    Route::post('/save-emprunt-manuel', [EmpruntController::class, 'saveEmpruntManuel'])->name('emprunt.saveEmpruntManuel');


    //Route for Users
    Route::post('membre/exclure', [UserController::class, 'excluremembre'])->name('user.exclure');
    Route::post('membre/deces', [UserController::class, 'decesmembre'])->name('user.deces');
    Route::post('membre/retraite', [UserController::class, 'retraitemembre'])->name('user.retraite');
    Route::post('membre/doubleauthdelete', [UserController::class, 'doubleauthdelete'])->name('user.doubleauthdelete');
    Route::get('/getuser', [UserController::class, 'getUser'])->name('getuser');
    Route::post('/get_user_pour_combobox', [UserController::class, 'getUserAjaxCombobox'])->name('getuserAjaxCombobox');
    Route::get('/get-membres-décédé', [UserController::class, 'getMembreDecedeAjax'])->name('getMembreDecedeAjax');
    Route::get('/Liste-des-membres-décédés', [UserController::class, 'getMembreDecede'])->name('membre.getMembreDecede');
    Route::get('/get-membres-retraité', [UserController::class, 'getMembreRetraiteAjax'])->name('getMembreRetraiteAjax');
    Route::get('/Liste-des-membres-retraités', [UserController::class, 'getMembreRetraite'])->name('membre.getMembreRetraite');
    Route::get('/get-membres-exclus', [UserController::class, 'getMembreExclusAjax'])->name('getMembreExclusAjax');
    Route::get('/Liste-des-membres-exclus', [UserController::class, 'getMembreExclus'])->name('membre.getMembreExclus');
    Route::resource('membre', UserController::class)->except(['show', 'update']);
    Route::get('membre/delete', function () {
        abort(404);
    });
    Route::get('membre/edit', function () {
        abort(404);
    });
    Route::get('membre/information', function () {
        abort(404);
    });
    Route::post('users/{id}', [UserController::class, 'update'])->name('membre.update');
    Route::get('membre/information/{id}', [UserController::class, 'infomembre'])->name('membre.info');

    //Route for categories
    Route::post('categories/delete', [CategoryController::class, 'deletecategory'])->name('categories.delete');
    Route::resource('categories', CategoryController::class)->except(['show', 'update', 'delete']);
    Route::get('/getcategoriesList', [CategoryController::class, 'getcategoriesList'])->name('getcategoriesList');
    Route::post('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/getCategories', [CategoryController::class, 'getCategories'])->name('getCategories');
    Route::get('categories/edit', function () {
        abort(404);
    });

    //Route for ayants droits
    Route::resource('ayantsdroits', AyantDroitController::class)->except(['show', 'update', 'delete', 'create']);
    Route::get('ayantsdroits/{id}', [AyantDroitController::class, 'create'])->name('ayantsdroits.create');
    Route::post('/getAyantsDroit/{id}', [AyantDroitController::class, 'getAyantsDroit'])->name('getAyantsDroit');
    Route::post('ayantsdroits/{ids}', [AyantDroitController::class, 'update'])->name('ayantsdroits.update');
    Route::post('activeayantdroit', [AyantDroitController::class, 'ayantdroitactive'])->name('ayantdroits.active');
    Route::post('deleteayantsdroits', [AyantDroitController::class, 'deleteayantdroits'])->name('ayantsdroits.delete');
    Route::get('ayantsdroits/delete', function () {
        abort(404);
    });
    Route::get('ayantsdroits/edit', function () {
        abort(404);
    });


    //Route for Type de prestation
    Route::resource('typeprestation', TypePrestationController::class)->except(['show', 'delete', 'update']);
    Route::get('/gettypeprestationList', [TypePrestationController::class, 'gettypeprestationList'])->name('gettypeprestationList');
    Route::post('typeprestation/delete', [TypePrestationController::class, 'deletetypeprestation'])->name('typeprestation.delete');
    Route::post('typeprestation/{id}', [TypePrestationController::class, 'update'])->name('typeprestation.update');
    Route::post('/getTypePrestations', [TypePrestationController::class, 'getTypePrestations'])->name('getTypePrestations');
    Route::get('typeprestation/delete', function () {
        abort(404);
    });
    Route::get('typeprestation/edit', function () {
        abort(404);
    });

    //Route for  prestation
    Route::resource('prestation', PrestationController::class)->except(['show', 'delete', 'update', 'create']);
    Route::get('/getprestationList', [PrestationController::class, 'getprestationList'])->name('getprestationList');
    Route::post('prestation/{ids}', [PrestationController::class, 'update'])->name('prestation.update');
    Route::post('deleteprestation', [PrestationController::class, 'deleteprestation'])->name('prestation.delete');
    Route::get('prestation/create/{id}', [PrestationController::class, 'create'])->name('prestation.create');

    Route::get('prestation/create', function () {
        abort(404);
    });
    Route::get('prestation/delete', function () {
        abort(404);
    });
    Route::get('prestation/edit', function () {
        abort(404);
    });
    Route::post('prestation/{id}', [PrestationController::class, 'update'])->name('prestation.update');
    Route::get('prestations/historique-annuel', [PrestationController::class, 'historiqueAnnuelPrestation'])->name('membre.historiqueprestationannuel');
    Route::get('prestations/historique-mensuel', [PrestationController::class, 'historiqueMensuelPrestation'])->name('membre.historiqueprestationmensuel');
    Route::get('prestations/historique-mensuel/detail-des-membres/{date}', [PrestationController::class, 'historiqueMensuelPrestationDetailMembre'])->name('membre.historiqueprestationmensuelDetailMembre');

    //requette ajax pour data table historique des prestations
    Route::get('historique-annuel-prestations', [PrestationController::class, 'getHistoriqueAnnuelPrestation'])->name('prestation.historique.annuel');
    Route::get('historique-mensuel-prestations', [PrestationController::class, 'getHistoriqueMensuelPrestation'])->name('prestation.historique.mensuel');
    Route::get('/getUserDetailPrestationHistoriqueMensuel/{date}', [PrestationController::class, 'getUserDetailPrestationHistoriqueMensuel'])->name('prestation.getUserDetailPrestationHistoriqueMensuel');

    //Route for cotisation
    Route::get('/getusercotisation/{date}', [CotisationController::class, 'getUserCotisation'])->name('getUserCotisation');
    Route::get('users/cotisations', [CotisationController::class, 'cotisation'])->name('membre.cotisation');
    Route::post('savecotisations', [CotisationController::class, 'savecotisation'])->name('membre.savecotisation');
    Route::post('deletecotisations', [CotisationController::class, 'deletecotisations'])->name('membre.deletecotisation');
    Route::get('cotisations/historique-annuel', [CotisationController::class, 'historiqueAnnuelCotisation'])->name('membre.historiquecotisationannuel');
    Route::get('cotisations/historique-mensuel', [CotisationController::class, 'historiqueMensuelCotisation'])->name('membre.historiquecotisationmensuel');
    Route::get('cotisations/historique-mensuel/detail-des-membres/{date}', [CotisationController::class, 'historiqueMensuelCotisationDetailMembre'])->name('membre.historiquecotisationmensuelDetailMembre');

    //requette ajax pour data table historique des cotisations
    Route::get('historique-annuel-cotisations', [CotisationController::class, 'getHistoriqueAnnuelCotisation'])->name('cotisation.historique.annuel');
    Route::get('historique-mensuel-cotisations', [CotisationController::class, 'getHistoriqueMensuelCotisation'])->name('cotisation.historique.mensuel');
    Route::get('/getUserDetailCotisationHistoriqueMensuel/{date}', [CotisationController::class, 'getUserDetailCotisationHistoriqueMensuel'])->name('cotisation.getUserDetailCotisationHistoriqueMensuel');

    //Route for caisse
    Route::get('caisse', [CaisseController::class, 'index'])->name('caisse.index');

    //Route for Dons
    Route::resource('dons', DonsController::class)->except(['show', 'delete', 'update']);
    Route::post('dons/{id}', [DonsController::class, 'update'])->name(
        'dons.update'
    );
    Route::get('/getdons', [DonsController::class, 'getDons'])->name('getDons');
    Route::post('deleteDonc', [DonsController::class, 'destroy'])->name('dons.delete');
});










Route::middleware(['auth:sanctum', 'verified'])->get('/set', function () {
    //App::setLocale('en');
    // dd($locale = App::currentLocale());
    return back();
});

//gere le mode dark et light
Route::middleware(['auth:sanctum', 'verified'])->get('/changeTheme', [AcceuilController::class, 'changeTheme'])->name('theme');

Route::get('changelang/{lang}', function ($lang) {
    App::setLocale('en');
    session()->put('locale', $lang);
    return redirect()->back();
})->name('lang');
Route::get('/lettre_souscription', function () {
    return view('pages.impressions.lettre_souscription');
});

// Route::get('send-mail', function () {

//     $details = [
//         'title' => 'Mail from ItSolutionStuff.com',
//         'body' => 'This is for testing email using smtp'
//     ];

//     \Mail::to("wtiam35@gmail.com")->send(new \App\Mail\MyTestMail($details));

//     dd("Email is Sent.");
// });