<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
        'id',
    ];

    public function likes() {
        return $this->belongsToMany(User::class, 'post_likes')->withTimestamps();
    }

    public function bookmarks() {
        return $this->belongsToMany(User::class, 'post_bookmarks')->withTimestamps();
    }
}
