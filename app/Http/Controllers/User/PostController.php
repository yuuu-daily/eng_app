<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use App\Services\UtilService;
use App\Services\AwsService;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (Gate::allows('system-admin')) {
        //     return redirect()->route('admin.award.index');
        // }
        // UtilService::isUser();
        $auth = Auth::user();
        $users = User::all();
        return Inertia::render('User/Post/Index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $auth = Auth::user();
        $parsed = parse_url($auth->profile_photo_path, PHP_URL_PATH);
        $s3key = ltrim($parsed, '/');
        $bucket = 'yuuu-cdn';
        $photo_url = AwsService::getSignedUrlFromKey($s3key, $bucket);
        $short_url = preg_replace(
            '/^.+\/([^\/\?]+)(\?.*)?$/',
            '$1',
            $photo_url
        );
        return Inertia::render('User/Post/Create', [
            'photo_url' => $photo_url,
            'short_url' => $short_url
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return Inertia::render('Admin/User/Edit', [
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // UtilService::isUser();
        $commonRules = [
            'photo_url' => [],
        ];
        $validated = $request->validate($commonRules);
        $user = User::find($id);
        $user->profile_photo_path = $validated['photo_url'];
        $user->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}