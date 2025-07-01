<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Providers\RouteServiceProvider;
// use Inertia\Inertia;
use App\Helpers\RouteHelper;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    return redirect(RouteServiceProvider::HOME);
});


Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    RouteHelper::setResourceRouteWithName('/admin/user', '\Admin\UserController');
    RouteHelper::setResourceRouteWithName('/admin/post', '\Admin\PostController');
    RouteHelper::setResourceRouteWithName('/admin/group', '\Admin\GroupController');
    RouteHelper::setResourceRouteWithName('/admin/category', '\Admin\CategoryController');
    RouteHelper::setResourceRouteWithName('/admin/event', '\Admin\EventController');
    RouteHelper::setResourceRouteWithName('/user/post', '\User\PostController');
    RouteHelper::setResourceRouteWithName('/user/event', '\User\EventController');
    RouteHelper::setResourceRouteWithName('/user/setting', '\User\UserController');
    // Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    //AWSで使うヤツなど
    Route::post('/util/get_presignedurl', [\App\Http\Controllers\Api\UtilController::class, 'getPreSignedUrl'])->name('api.util.get_presignedurl');
});
