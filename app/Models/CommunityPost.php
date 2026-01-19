<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'status',
    ];

    protected static function booted()
    {
        static::deleting(function ($post) {
            // Delete all comments and their reports when post is deleted
            $post->comments()->each(function ($comment) {
                $comment->reports()->delete();
                $comment->delete();
            });
            // Delete post reports
            $post->reports()->delete();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(CommunityComment::class, 'post_id');
    }

    public function likes()
    {
        return $this->hasMany(CommunityLike::class, 'post_id');
    }

    public function reports()
    {
        return $this->hasMany(PostReport::class, 'post_id');
    }
}
