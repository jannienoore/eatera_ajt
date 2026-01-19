<?php

namespace App\Http\Controllers;

use App\Models\FoodJournal;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodJournalController extends Controller
{
    public function store(Request $request)
    {
    $validated = $request->validate([
        'food_id'   => 'required|exists:foods,id',
        'quantity'  => 'nullable|numeric|min:0.1',
        'unit'      => 'nullable|in:gram',
        'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
        'eaten_at'  => 'required|date',
    ]);

    // CEK: makan utama cuma boleh 1x per hari
    if (in_array($validated['meal_type'], ['breakfast','lunch','dinner'])) {
        $exists = FoodJournal::where('user_id', $request->user()->id)
            ->whereDate('eaten_at', $validated['eaten_at'])
            ->where('meal_type', $validated['meal_type'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => ucfirst($validated['meal_type']) . ' already exists for this day'
            ], 422);
        }
    }

    // CEK: Jangan melebihi target kalori harian
    $food = Food::find($validated['food_id']);
    $newCalories = $food->calories * ($validated['quantity'] ?? 1) / 100;
    
    $totalCalories = FoodJournal::join('foods', 'food_journals.food_id', '=', 'foods.id')
        ->where('food_journals.user_id', $request->user()->id)
        ->whereDate('food_journals.eaten_at', $validated['eaten_at'])
        ->selectRaw('SUM(foods.calories * food_journals.quantity / 100) as total')
        ->value('total') ?? 0;
    
    $targetCalories = optional($request->user()->profile)->target_calories ?? 2000;
    
    if (($totalCalories + $newCalories) >= $targetCalories) {
        return response()->json([
            'message' => 'Anda sudah mencapai atau melebihi target kalori harian. Input makanan tidak dapat dilakukan.',
            'current_calories' => round($totalCalories, 2),
            'target_calories' => $targetCalories,
            'would_exceed' => true
        ], 422);
    }

    $journal = FoodJournal::create([
        'user_id'   => $request->user()->id,
        'food_id'   => $validated['food_id'],
        'quantity'  => $validated['quantity'] ?? 1,
        'unit'      => 'gram',
        'meal_type' => $validated['meal_type'],
        'eaten_at'  => $validated['eaten_at'],
    ]);

    return response()->json($journal, 201);
    }


    public function index(Request $request)
    {
    $date = $request->query('date', now()->toDateString());

    $journals = FoodJournal::with('food')
        ->where('user_id', $request->user()->id)
        ->whereDate('eaten_at', $date)
        ->orderByRaw("FIELD(meal_type, 'breakfast','lunch','dinner','snack')")
        ->get();

    return response()->json($journals);
    }


    public function all(Request $request)
    {
    return FoodJournal::with('food')
        ->where('user_id', $request->user()->id)
        ->orderBy('eaten_at', 'desc')
        ->get();
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'food_id'   => 'required|exists:foods,id',
            'quantity'  => 'nullable|numeric|min:0.1',
            'unit'      => 'nullable|in:gram',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'eaten_at'  => 'required|date',
        ]);

        $journal = FoodJournal::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$journal) {
            return response()->json(['message' => 'Not found'], 404);
        }

        // CEK: jika meal_type atau eaten_at berubah, cek jangan duplicate
        if ($journal->meal_type !== $validated['meal_type'] || 
            $journal->eaten_at->toDateString() !== $validated['eaten_at']) {
            
            if (in_array($validated['meal_type'], ['breakfast','lunch','dinner'])) {
                $exists = FoodJournal::where('user_id', $request->user()->id)
                    ->where('id', '!=', $id)
                    ->whereDate('eaten_at', $validated['eaten_at'])
                    ->where('meal_type', $validated['meal_type'])
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'message' => ucfirst($validated['meal_type']) . ' already exists for this day'
                    ], 422);
                }
            }
        }

        // CEK: Jangan melebihi target kalori harian saat update
        $food = Food::find($validated['food_id']);
        $oldCalories = $journal->food->calories * $journal->quantity / 100;
        $newCalories = $food->calories * ($validated['quantity'] ?? 1) / 100;
        $caloriesDifference = $newCalories - $oldCalories;
        
        $totalCalories = FoodJournal::join('foods', 'food_journals.food_id', '=', 'foods.id')
            ->where('food_journals.user_id', $request->user()->id)
            ->whereDate('food_journals.eaten_at', $validated['eaten_at'])
            ->selectRaw('SUM(foods.calories * food_journals.quantity / 100) as total')
            ->value('total') ?? 0;
        
        $targetCalories = optional($request->user()->profile)->target_calories ?? 2000;
        
        if (($totalCalories + $caloriesDifference) >= $targetCalories) {
            return response()->json([
                'message' => 'Perubahan ini akan membuat Anda mencapai atau melebihi target kalori harian.',
                'current_calories' => round($totalCalories, 2),
                'target_calories' => $targetCalories,
                'would_exceed' => true
            ], 422);
        }

        $journal->update([
            'food_id'   => $validated['food_id'],
            'quantity'  => $validated['quantity'] ?? 1,
            'unit'      => 'gram',
            'meal_type' => $validated['meal_type'],
            'eaten_at'  => $validated['eaten_at'],
        ]);

        return response()->json($journal, 200);
    }
}