<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Food extends Model
{
    use HasFactory;
    protected $table = 'foods';

    protected $fillable = [
        'name',
        'usda_fdc_id',
        'calories',
        'protein',
        'fat',
        'carbohydrates',
        'fiber',
        'source',
        'serving_size',
        'serving_description',
    ];

    public function journals()
    {
    return $this->hasMany(FoodJournal::class);
    }

}

