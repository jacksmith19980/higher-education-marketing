<?php

use App\Http\Controllers\AjaxController;
//use App\Http\Controllers\Auth;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SalesContactController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    session()->forget('tenant');

    return view('back.auth.login');
});

//Route::group(['domain' => 'portal.higher-education-marketing.com'], function () {
//    Route::get('/landingpage', [Auth\LandingPageController::class, 'showLandingPage'])->name('landingpage');
//});
Route::get('/free-trial', [App\Http\Controllers\Auth\LandingPageController::class, 'showLandingPage'])->name('landingpage');


Route::get('/auth/activate-account', [App\Http\Controllers\Auth\LoginController::class, 'activate'])->name('activate.user.account');

Auth::routes();


Route::get('submissions/applicants/files/view', [App\Http\Controllers\Tenant\FileController::class, 'showFile'])
 ->name('file.show');


Route::post('/register-landing', [App\Http\Controllers\Auth\LandingPageController::class, 'create'])->name('register-landing');
Route::post('/payment', [App\Http\Controllers\Auth\LandingPageController::class, 'payment'])->name('landing.payment');

Route::middleware('auth')->group(function () {

    // Home
    Route::get('/home', [CoreController::class, 'home'])->name('home');

    // Schools Resource (CRUD)
    Route::resource('schools', SchoolController::class);

    Route::get('/plans/select', [PlanController::class, 'select'])->name('select.plan');
    Route::get('/plans/{plan}/selected', [PlanController::class, 'selected'])->name('selected.plan');

    // Plan payment
    Route::get('/plans/payment', [PaymentController::class, 'paymentStripe'])->name('payment.show.plan');
    Route::post('/plans/payment', [PaymentController::class, 'paymentProcess'])->name('payment.process.plan');



    // Plans Resource (CRUD)
    Route::resource('plans', PlanController::class);

    // Switch Tenant
    Route::get('/school/{school}', [TenantController::class, 'switch'])->name('tenant.switch');
    Route::post('/ajax/', [AjaxController::class, 'excuate'])->name('ajax');
    Route::post('/upload/', [App\Http\Controllers\Tenant\School\UploadController::class, 'upload'])->name('upload');
    Route::post('/destroy/', [App\Http\Controllers\Tenant\School\UploadController::class, 'destroy'])->name('destroy');

    Route::get('/me/', [App\Http\Controllers\ProfileController::class, 'profile'])->name('user.profile');
    Route::post('/me/', [App\Http\Controllers\ProfileController::class, 'update']);

    Route::get('users', [UserController::class, 'index'])
        ->name('users.index');

    Route::get('users/new', [UserController::class, 'create'])
        ->name('users.create');

    Route::post('users/check-exists', [UserController::class, 'checkEmailNotExists'])
        ->name('users.check.exists');

    Route::post('users/new', [UserController::class, 'store'])
        ->name('users.store');

    Route::get('users/{user}/edit', [UserController::class, 'edit'])
        ->name('users.edit');

    Route::post('users/{user}/update', [UserController::class, 'update'])
        ->name('users.update');

    Route::delete('users/{user}/destroy', [UserController::class, 'destroy'])
        ->name('users.destroy');


    Route::get('/auth/{user}', [App\Http\Controllers\Auth\ActionsController::class, 'destroy'])->name('delete.user');
    Route::get('/auth/activate/{user}', [App\Http\Controllers\Auth\ActionsController::class, 'activate'])->name('activate.user');
    Route::get('/auth/deactivate/{user}', [App\Http\Controllers\Auth\ActionsController::class, 'deactivate'])->name('deactivate.user');
});

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return 'Cleared';
});
