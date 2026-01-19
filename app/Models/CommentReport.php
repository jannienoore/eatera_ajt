<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'post_id',
        'reported_by',
        'reason',
    ];

    public function comment()
    {
        return $this->belongsTo(CommunityComment::class, 'comment_id');
    }

    public function post()
    {
        return $this->belongsTo(CommunityPost::class, 'post_id');
    }

    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
