<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AhpRecommendationService;

class RecommendationController extends Controller
{
    public function today(Request $request, AhpRecommendationService $service)
    {
        $user = $request->user();

        // Hitung kalori yang sudah dikonsumsi hari ini (per 100 gram)
        $consumedCalories = $user->foodJournals()
            ->whereDate('eaten_at', today())
            ->with('food')
            ->get()
            ->sum(function ($journal) {
                if (!$journal->food) return 0;
                
                $quantity = $journal->quantity;
                $unit = $journal->unit ?? 'gram';
                
                // Konversi ke gram jika perlu
                if ($unit === 'gram') {
                    $gramsUsed = $quantity;
                } elseif ($unit === 'ml') {
                    // Assume 1 ml ≈ 1 gram untuk liquid (approximation)
                    $gramsUsed = $quantity;
                } elseif ($unit === 'pcs' || $unit === 'pieces') {
                    // Untuk pieces, assume standard serving (150g default)
                    $gramsUsed = $quantity * 150;
                } else {
                    $gramsUsed = $quantity;
                }
                
                // Calories dalam database adalah per 100g
                return ($journal->food->calories * $gramsUsed) / 100;
            });

        $foods = $service->recommend($user);

        return response()->json([
            'date' => now()->toDateString(),
            'diet_goal' => $user->profile->diet_goal,
            'target_calories' => $user->profile->target_calories,
            'consumed_calories' => round($consumedCalories, 2),
            'remaining_calories' => max(
                $user->profile->target_calories - $consumedCalories,
                0
            ),
            'recommendations' => $foods
        ]);
    }
}
