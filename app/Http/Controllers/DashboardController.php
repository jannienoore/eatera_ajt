<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FoodJournal;
use Illuminate\Support\Facades\DB;
use App\Models\UserProfile;

class DashboardController extends Controller
{
    public function daily(Request $request)
{
    $date = $request->query('date', now()->toDateString());
    $userId = $request->user()->id;

    // ======================
    // TOTAL HARIAN
    // ======================
    $summary = FoodJournal::join('foods', 'food_journals.food_id', '=', 'foods.id')
        ->where('food_journals.user_id', $userId)
        ->whereDate('food_journals.eaten_at', $date)
        ->select(
            DB::raw('SUM(foods.calories * food_journals.quantity / 100) as calories'),
            DB::raw('SUM(foods.protein * food_journals.quantity / 100) as protein'),
            DB::raw('SUM(foods.fat * food_journals.quantity / 100) as fat'),
            DB::raw('SUM(foods.carbohydrates * food_journals.quantity / 100) as carbohydrates'),
            DB::raw('SUM(foods.fiber * food_journals.quantity / 100) as fiber')
        )
        ->first();

    // ======================
    // PER MEAL
    // ======================
    $mealsRaw = FoodJournal::join('foods', 'food_journals.food_id', '=', 'foods.id')
        ->where('food_journals.user_id', $userId)
        ->whereDate('food_journals.eaten_at', $date)
        ->groupBy('food_journals.meal_type')
        ->select(
            'food_journals.meal_type',
            DB::raw('SUM(foods.calories * food_journals.quantity / 100) as calories'),
            DB::raw('SUM(foods.protein * food_journals.quantity / 100) as protein'),
            DB::raw('SUM(foods.fat * food_journals.quantity / 100) as fat'),
            DB::raw('SUM(foods.carbohydrates * food_journals.quantity / 100) as carbohydrates'),
            DB::raw('SUM(foods.fiber * food_journals.quantity / 100) as fiber')
        )
        ->get()
        ->keyBy('meal_type');

    // DEFAULT VALUE JIKA BELUM MAKAN
    $mealTypes = ['breakfast','lunch','dinner','snack'];
    $meals = [];

    foreach ($mealTypes as $type) {
        $meals[$type] = [
            'calories' => round($mealsRaw[$type]->calories ?? 0, 2),
            'protein' => round($mealsRaw[$type]->protein ?? 0, 2),
            'fat' => round($mealsRaw[$type]->fat ?? 0, 2),
            'carbohydrates' => round($mealsRaw[$type]->carbohydrates ?? 0, 2),
            'fiber' => round($mealsRaw[$type]->fiber ?? 0, 2),
        ];
    }

    // ======================
    // TARGET & PROGRESS
    // ======================
    $targetCalories = optional($request->user()->profile)->target_calories ?? 2000;
    $totalCalories = $summary->calories ?? 0;
    $isExceeded = $totalCalories > $targetCalories;

    $progress = $targetCalories > 0
        ? round(($totalCalories / $targetCalories) * 100, 1)
        : 0;

    return response()->json([
        'date' => $date,
        'summary' => [
            'calories' => round($totalCalories, 2),
            'protein' => round($summary->protein ?? 0, 2),
            'fat' => round($summary->fat ?? 0, 2),
            'carbohydrates' => round($summary->carbohydrates ?? 0, 2),
            'fiber' => round($summary->fiber ?? 0, 2),
        ],
        'meals' => $meals,
        'target_calories' => $targetCalories,
        'progress_percent' => $progress,
        'is_exceeded' => $isExceeded,
    ]);
}

    public function weekly(Request $request)
    {
        $userId = $request->user()->id;
        $start = $request->query('start', now()->startOfWeek()->toDateString());
        $end = \Carbon\Carbon::parse($start)->addDays(6)->toDateString();

        $data = FoodJournal::join('foods', 'food_journals.food_id', '=', 'foods.id')
            ->where('food_journals.user_id', $userId)
            ->whereBetween('food_journals.eaten_at', [$start, $end])
            ->groupBy('food_journals.eaten_at')
            ->orderBy('food_journals.eaten_at')
            ->select(
                'food_journals.eaten_at as date',
                DB::raw('SUM(foods.calories * food_journals.quantity / 100) as calories')
            )
            ->get();

        $average = round($data->avg('calories') ?? 0, 2);

        return response()->json([
            'range' => "$start to $end",
            'daily' => $data,
            'average_calories' => $average,
            'target_calories' => optional($request->user()->profile)->target_calories ?? 2000
        ]);
    }
    
}
