<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/**
 * ziggyでLaravelのrouteを使えるのdが、nameを指定する必要がある。nameを使わないのであれば不要(Route::resourceのままがシンプルでよい)。
 * @param $base_route
 * @param $base_controller
 * @return void
 */
function setResourceRouteWithName($base_route, $base_controller): void
{
    $base_namespace = ltrim(str_replace('/', '.', $base_route), '.');
    Route::resource($base_route, '\App\Http\Controllers' . $base_controller)->names([
        'index' => $base_namespace . '.index',
        'create' => $base_namespace . '.create',
        'show' => $base_namespace . '.show',
        'edit' => $base_namespace . '.edit',
        'update' => $base_namespace . '.update',
        'destroy' => $base_namespace . '.destroy',
        'store' => $base_namespace . '.store'
    ]);
}


Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    setResourceRouteWithName('/admin/user', '\admin\UserController');
    // Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
});
