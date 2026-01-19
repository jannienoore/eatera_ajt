<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoodJournal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'food_id',
        'quantity',
        'meal_type',
        'eaten_at',
    ];

    protected $casts = [
        'eaten_at' => 'date',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

