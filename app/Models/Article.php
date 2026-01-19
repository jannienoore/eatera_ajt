<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'cover_image',
        'status',
        'admin_id',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
