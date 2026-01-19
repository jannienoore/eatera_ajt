<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'content',
    ];

    protected static function booted()
    {
        static::deleting(function ($comment) {
            // Delete all reports for this comment when comment is deleted
            $comment->reports()->delete();
        });
    }

    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->hasMany(CommentReport::class, 'comment_id');
    }
}
