<?php

use App\Http\Controllers\{AcceuilController, AyantDroitController, CategoryController, CotisationController, PrestationController, TypePrestationController, UserController};
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
    }
);
// Route::middleware(['auth:sanctum', 'verified'])->get('/', [AcceuilController::class, 'index'])->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'admin'])->prefix('administration')->group(function () {
    //Route for Users
    Route::post('membre/delete', [UserController::class, 'deletemembre'])->name('user.delete');
    Route::post('membre/doubleauthdelete', [UserController::class, 'doubleauthdelete'])->name('user.doubleauthdelete');
    Route::get('/getuser', [UserController::class, 'getUser'])->name('getuser');
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

    //Route for cotisation
    Route::get('/getusercotisation/{date}', [CotisationController::class, 'getUserCotisation'])->name('getUserCotisation');
    Route::get('users/cotisations', [CotisationController::class, 'cotisation'])->name('membre.cotisation');
    Route::post('savecotisations', [CotisationController::class, 'savecotisation'])->name('membre.savecotisation');
    Route::POST('deletecotisations', [CotisationController::class, 'deletecotisations'])->name('membre.deletecotisation');
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

// Route::get('send-mail', function () {

//     $details = [
//         'title' => 'Mail from ItSolutionStuff.com',
//         'body' => 'This is for testing email using smtp'
//     ];

//     \Mail::to("wtiam35@gmail.com")->send(new \App\Mail\MyTestMail($details));

//     dd("Email is Sent.");
// });