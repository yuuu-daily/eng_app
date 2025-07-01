<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use App\Models\CategoryPost;
use Inertia\Inertia;
use App\Services\UtilService;
use App\Services\AwsService;
use App\Repositories\PostRepository;

class UserController extends Controller
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
        $posts = PostRepository::gets();
        foreach ($posts as &$post) {
            if (!empty($post->profile_photo_path)) {
                $parsed = parse_url($post->profile_photo_path, PHP_URL_PATH);
                $s3key = ltrim($parsed, '/');
                $bucket = 'yuuu-cdn';
                $post->profile_photo_path = AwsService::getSignedUrlFromKey($s3key, $bucket);
            }
        }
        return Inertia::render('User/Post/Index', [
            'users' => $users,
            'posts' => $posts
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
            '/https?:\/\/[^\/]+\/[^\/]+\/uploads\/\d+_\d{8}T\d{5}_(.+\.\w+)$/',
            '$1',
            $photo_url
        );
        $categories = Category::all();
        return Inertia::render('User/Post/Create', [
            'photo_url' => $photo_url,
            'short_url' => $short_url,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //UtilService::isAdminOwner();
        $auth = Auth::user();
        $validated = $request->validate([
            'title' => ['required'],
            'category_ids' => ['required'],
            'category_changed' => [],
            'content' => [],
        ]);
        $post = Post::create([
            "user_id" => $auth->id,
            "title" => $validated['title'],
            "content" => $validated['content'],
        ]);
        if ($validated['category_changed']) {
            $categories = $validated['category_ids'];
            foreach ($categories as $c) {
                CategoryPost::create([
                    "category_id" => $c['id'],
                    "post_id" => $post->id,
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = PostRepository::findPost($id);
        if (!empty($post->profile_photo_path)) {
            $parsed = parse_url($post->profile_photo_path, PHP_URL_PATH);
            $s3key = ltrim($parsed, '/');
            $bucket = 'yuuu-cdn';
            $post->profile_photo_path = AwsService::getSignedUrlFromKey($s3key, $bucket);
        }
        return Inertia::render('User/Post/Show', [
            'post' => $post
        ]);
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
