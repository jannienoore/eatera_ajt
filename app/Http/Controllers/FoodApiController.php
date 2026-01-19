<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Services\UsdaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FoodApiController extends Controller
{
    protected $usda;

    public function __construct(UsdaService $usda)
    {
        $this->usda = $usda;
    }

    public function search(Request $request)
    {
        $query = $request->query('food');

        if (!$query) {
            return response()->json(['error' => 'Query parameter food is required'], 422);
        }

        $result = $this->usda->searchFood($query);

        return response()->json($result);
    }

    public function import(Request $request)
    {
        $request->validate([
            'fdcId' => 'required|numeric'
        ]);

        $fdcId = $request->fdcId;

        $data = $this->usda->getFoodDetail($fdcId);

        if (!isset($data['description'])) {
            return response()->json([
                'error' => 'Invalid fdcId',
                'detail' => $data
            ], 400);
        }

        // Extract nutrients (USDA format)
        $nutrients = $data['foodNutrients'] ?? [];

        $calories = null;
        $protein = null;
        $fat = null;
        $carbohydrates = null;
        $fiber = null;

        foreach ($nutrients as $n) {
            $name = $n['nutrient']['name'];

            if ($name === 'Energy') {
                $calories = $n['amount'];
            }
            if ($name === 'Protein') {
                $protein = $n['amount'];
            }
            if ($name === 'Total lipid (fat)') {
                $fat = $n['amount'];
            }
            if ($name === 'Carbohydrate, by difference') {
                $carbohydrates = $n['amount'];
            }
            if ($name === 'Fiber, total dietary') {
            $fiber = $n['amount'];
            }
        }

        // Extract serving size from USDA data
        $servingSize = 100; // Default to 100g
        $servingDescription = null;
        
        if (isset($data['servingSize'])) {
            $servingSize = $data['servingSize'];
        }
        if (isset($data['servingSizeUnit'])) {
            $servingDescription = $servingSize . ' ' . $data['servingSizeUnit'];
        }

        $food = Food::updateOrCreate(
            ['usda_fdc_id' => $fdcId],
            [
                'name' => $data['description'],
                'calories' => $calories,
                'protein' => $protein,
                'fat' => $fat,
                'carbohydrates' => $carbohydrates,
                'fiber' => $fiber,
                'source' => 'usda',
                'serving_size' => $servingSize,
                'serving_description' => $servingDescription,
            ]
        );

        return response()->json($food, 201);
    }
}
