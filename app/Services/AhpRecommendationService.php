<?php

namespace App\Services;

use App\Models\Food;
use App\Models\FoodJournal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AhpRecommendationService
{
    public function recommend($user, int $limit = 5)
    {
        $profile = $user->profile;

        $dietGoal = $profile->diet_goal;
        $targetCalories = $profile->target_calories;

        // ================= HITUNG SISA KALORI =================
        $consumedCalories = $user->foodJournals()
            ->whereDate('eaten_at', Carbon::today())
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
                    // Assume 1 ml ≈ 1 gram untuk liquid
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

        $remainingCalories = max($targetCalories - $consumedCalories, 0);

        // Jika sisa kalori sudah habis/hampir habis
        if ($remainingCalories < 50) {
            return collect();
        }

        // ================= RANGE KALORI BERDASARKAN DIET GOAL =================
        if ($dietGoal === 'deficit') {
            // Deficit: rekomendasikan makanan lebih kecil (snack/light meal)
            $minCalories = max($remainingCalories * 0.05, 30);
            $maxCalories = max($remainingCalories * 0.50, 120);
        } elseif ($dietGoal === 'bulking') {
            // Bulking: rekomendasikan makanan lebih besar
            $minCalories = max($remainingCalories * 0.20, 100);
            $maxCalories = max($remainingCalories * 0.90, 300);
        } else {
            // Maintain: balanced
            $minCalories = max($remainingCalories * 0.10, 60);
            $maxCalories = max($remainingCalories * 0.70, 250);
        }

        $foods = Food::whereBetween('calories', [
            $minCalories,
            $maxCalories,
        ])->get();

        if ($foods->isEmpty()) {
            return collect();
        }

        $weights = $this->weights()[$dietGoal];
        $types   = $this->criteriaTypes()[$dietGoal];

        // ================= STATISTIK =================
        $stats = [];
        foreach ($weights as $key => $w) {
            $stats[$key] = [
                'max' => max($foods->max($key), 1),
                'min' => max($foods->min($key), 1),
            ];
        }

        // ================= HITUNG SKOR AHP =================
        $scoredFoods = $foods->map(function ($food) use ($weights, $types, $stats) {
            $score = 0;

            foreach ($weights as $key => $weight) {
                $value = max($food->$key, 0.1); // Hindari division by zero

                // Normalisasi berdasarkan tipe kriteria
                if ($types[$key] === 'benefit') {
                    // Semakin tinggi semakin baik
                    $normalized = $value / max($stats[$key]['max'], 0.1);
                } else {
                    // Semakin rendah semakin baik (cost)
                    $normalized = max($stats[$key]['min'], 0.1) / $value;
                }

                $score += $normalized * $weight;
            }

            $food->ahp_score = round($score, 4);
            return $food;
        });

        // ================= VARIASI MENU =================
        return $scoredFoods
            ->sortByDesc('ahp_score')   // AHP tetap utama
            ->take(20)                  // ambil kandidat terbaik
            ->shuffle()                 // acak untuk variasi
            ->take($limit)
            ->values();
    }

    private function weights(): array
    {
        return [
            'deficit' => [
                'protein' => 0.35,   // Protein penting untuk satiety
                'fiber' => 0.20,     // Serat bantu kenyang
                'calories' => 0.25,  // Kalori harus rendah untuk deficit
                'fat' => 0.15,       // Fat control
                'carbohydrates' => 0.05,
            ],
            'maintain' => [
                'calories' => 0.25,
                'protein' => 0.25,
                'carbohydrates' => 0.20,
                'fat' => 0.20,
                'fiber' => 0.10,
            ],
            'bulking' => [
                'calories' => 0.30,  // Kalori prioritas untuk surplus
                'protein' => 0.30,
                'carbohydrates' => 0.25,
                'fat' => 0.10,
                'fiber' => 0.05,
            ],
        ];
    }

    private function criteriaTypes(): array
    {
        return [
            'deficit' => [
                'calories' => 'cost',          // Semakin rendah semakin baik
                'protein' => 'benefit',        // Semakin tinggi semakin baik
                'fat' => 'cost',               // Semakin rendah semakin baik
                'carbohydrates' => 'cost',     // Semakin rendah semakin baik
                'fiber' => 'benefit',          // Semakin tinggi semakin baik
            ],
            'maintain' => [
                'calories' => 'benefit',
                'protein' => 'benefit',
                'fat' => 'benefit',
                'carbohydrates' => 'benefit',
                'fiber' => 'benefit',
            ],
            'bulking' => [
                'calories' => 'benefit',       // Semakin tinggi semakin baik
                'protein' => 'benefit',
                'fat' => 'benefit',
                'carbohydrates' => 'benefit',
                'fiber' => 'cost',             // Semakin rendah semakin baik (fiber bisa mengenyangkan)
            ],
        ];
    }
}
