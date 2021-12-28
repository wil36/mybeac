<?php

use App\Http\Controllers\{AcceuilController, AyantDroitController, CategoryController, UserController};
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
Route::middleware(['auth:sanctum', 'verified'])->post('/getCategories', [CategoryController::class, 'getCategories'])->name('getCategories');

Route::middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {
    //Route for Users
    Route::get('users/delete/{id}', [UserController::class, 'destroys'])->name('user.delete');
    Route::get('users/doubleauthdelete/{id}', [UserController::class, 'doubleauthdelete'])->name('user.doubleauthdelete');
    Route::get('/getuser', [UserController::class, 'getUser'])->name('getuser');
    Route::resource('users', UserController::class)->except(['show', 'update']);
    Route::post('users/{id}', [UserController::class, 'update'])->name('users.update');

    //Route for categories
    Route::get('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.delete');
    Route::resource('categories', CategoryController::class)->except(['show', 'update', 'delete']);
    Route::get('/getcategoriesList', [CategoryController::class, 'getcategoriesList'])->name('getcategoriesList');
    Route::post('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');

    //Route for ayants droits
    Route::resource('ayantsdroits', AyantDroitController::class)->except(['show', 'update', 'delete', 'create']);
    Route::get('ayantsdroits/{id}', [AyantDroitController::class, 'create'])->name('ayantsdroits.create');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/', [AcceuilController::class, 'index'])->name('dashboard');
Route::middleware(['auth:sanctum', 'verified'])->get('/set', function () {
    //App::setLocale('en');
    dd($locale = App::currentLocale());
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