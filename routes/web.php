<?php

use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SalesProcessingController;

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

// Route::get('/', function () {
//     return view('login');
// });
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/register', [LoginController::class, 'showForm']);
Route::post('/register', [LoginController::class, 'store'])->name('register.store');

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

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
    Route::get('/get-processing', [SalesProcessingController::class, 'getprocessing'])->name('get-processing');
    Route::get('/admin/search-basic-plan', [SalesProcessingController::class, 'searchBasicPlan'])->name('search.basic.plan');
    Route::post('/submit-premium-selection', [SalesProcessingController::class, 'storePremiumSelection'])->name('submit.premium.selection');
});