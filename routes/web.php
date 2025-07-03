<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/admin', [CustomerController::class, 'index'])->name('admin.dashboard');
    Route::resource('customers', CustomerController::class);

    Route::get('/get-plan', [CustomerController::class, 'getPlan'])->name('get-plan');
    Route::post('/plans', [CustomerController::class, 'storePlan'])->name('plans.store');
    Route::put('/plans/{id}', [CustomerController::class, 'updatePlan'])->name('plans.update');

    Route::get('/get-processing', [CustomerController::class, 'getprocessing'])->name('get-processing');
    Route::get('/admin/search-basic-plan', [CustomerController::class, 'searchBasicPlan'])->name('search.basic.plan');
    Route::post('/submit-premium-selection', [CustomerController::class, 'storePremiumSelection'])->name('submit.premium.selection');

    // routes/web.php

    Route::get('/api/sagment/{id}/data', [CustomerController::class, 'getBySagment']);

});