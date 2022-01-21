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

Route::middleware(['auth:sanctum', 'verified'])->get('/', [AcceuilController::class, 'index'])->name('dashboard');

Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {
    //Route for Users
    Route::get('users/delete/{id}', [UserController::class, 'destroys'])->name('user.delete');
    Route::get('users/doubleauthdelete/{id}', [UserController::class, 'doubleauthdelete'])->name('user.doubleauthdelete');
    Route::get('/getuser', [UserController::class, 'getUser'])->name('getuser');
    Route::resource('users', UserController::class)->except(['show', 'update']);
    Route::post('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('membre/information/{id}', [UserController::class, 'infomembre'])->name('membre.info');

    //Route for categories
    Route::get('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
    Route::resource('categories', CategoryController::class)->except(['show', 'update', 'delete']);
    Route::get('/getcategoriesList', [CategoryController::class, 'getcategoriesList'])->name('getcategoriesList');
    Route::post('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/getCategories', [CategoryController::class, 'getCategories'])->name('getCategories');

    //Route for ayants droits
    Route::resource('ayantsdroits', AyantDroitController::class)->except(['show', 'update', 'delete', 'create']);
    Route::get('ayantsdroits/{id}', [AyantDroitController::class, 'create'])->name('ayantsdroits.create');
    Route::get('ayantsdroitsListForUser/{id}', [AyantDroitController::class, 'ayantsdroitsListForUser'])->name('ayantsdroitsListForUser');
    Route::post('/getAyantsDroit/{id}', [AyantDroitController::class, 'getAyantsDroit'])->name('getAyantsDroit');
    Route::post('ayantsdroits/{ids}', [AyantDroitController::class, 'update'])->name('ayantsdroits.update');
    Route::get('ayantsdroits/delete/{id}', [AyantDroitController::class, 'destroy'])->name('ayantsdroits.delete');

    //Route for Type de prestation
    Route::resource('typeprestation', TypePrestationController::class)->except(['show', 'delete', 'update']);
    Route::get('/gettypeprestationList', [TypePrestationController::class, 'gettypeprestationList'])->name('gettypeprestationList');
    Route::get('typeprestation/delete/{id}', [TypePrestationController::class, 'destroy'])->name('typeprestation.delete');
    Route::post('typeprestation/{id}', [TypePrestationController::class, 'update'])->name('typeprestation.update');
    Route::post('/getTypePrestations', [TypePrestationController::class, 'getTypePrestations'])->name('getTypePrestations');

    //Route for  prestation
    Route::resource('prestation', PrestationController::class)->except(['show', 'delete', 'update', 'create']);
    Route::get('/getprestationList', [PrestationController::class, 'getprestationList'])->name('getprestationList');
    Route::get('/getprestationListForUser/{id}', [PrestationController::class, 'getprestationListForUser'])->name('getprestationListForUser');
    Route::post('prestation/{ids}', [PrestationController::class, 'update'])->name('prestation.update');
    Route::get('prestation/delete/{id}', [PrestationController::class, 'destroy'])->name('prestation.delete');
    Route::get('prestation/create/{id}', [PrestationController::class, 'create'])->name('prestation.create');
    Route::get('prestation/create', function () {
        abort(404);
    });
    Route::get('prestation/delete', function () {
        abort(404);
    });
    Route::post('prestation/{id}', [PrestationController::class, 'update'])->name('prestation.update');

    //Route for cotisation
    Route::get('/getusercotisation', [CotisationController::class, 'getUserCotisation'])->name('getUserCotisation');
    Route::get('users/cotisations', [CotisationController::class, 'cotisation'])->name('users.cotisation');
    Route::post('savecotisations', [CotisationController::class, 'savecotisation'])->name('users.savecotisation');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/', [AcceuilController::class, 'index'])->name('dashboard');
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