<?php

namespace App\Http\Controllers;

use App\Models\Food;

class FoodController extends Controller
{
    public function index()
    {
        return response()->json(
            Food::select(
                'id',
                'name',
                'calories',
                'protein',
                'fat',
                'carbohydrates',
                'fiber',
                'serving_size',
                'serving_description'
            )->get()
        );
    }

    public function show($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }

        return response()->json($food);
    }
}
