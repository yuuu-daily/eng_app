<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\PendingResourceRegistration;

class RouteHelper
{
    public static function setResourceRouteWithName($base_route, $base_controller): PendingResourceRegistration
    {
        $base_namespace = ltrim(str_replace('/', '.', $base_route), '.');

        return Route::resource($base_route, '\App\Http\Controllers' . $base_controller)->names([
            'index'   => $base_namespace . '.index',
            'create'  => $base_namespace . '.create',
            'show'    => $base_namespace . '.show',
            'edit'    => $base_namespace . '.edit',
            'update'  => $base_namespace . '.update',
            'destroy' => $base_namespace . '.destroy',
            'store'   => $base_namespace . '.store'
        ]);
    }
}