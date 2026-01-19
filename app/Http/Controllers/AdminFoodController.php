<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class AdminFoodController extends Controller
{
    /**
     * GET /api/admin/foods
     * List all foods
     */
    public function index()
    {
        return response()->json(Food::all()
);
    }

    /**
     * GET /api/admin/foods/{id}
     * Show one food
     */
    public function show($id)
    {
        $food = Food::find($id);

        if (!$food) {
            return response()->json(['message' => 'Food not found'], 404);
        }

        return response()->json($food);
    }

    /**
     * POST /api/admin/foods
     * Create new food manually
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'calories'      => 'nullable|numeric',
            'protein'       => 'nullable|numeric',
            'fat'           => 'nullable|numeric',
            'carbohydrates' => 'nullable|numeric',
            'fiber'         => 'nullable|numeric',
        ]);

        $food = Food::create([
            'name'          => $request->name,
            'usda_fdc_id'   => $request->usda_fdc_id,
            'calories'      => $request->calories,
            'protein'       => $request->protein,
            'fat'           => $request->fat,
            'carbohydrates' => $request->carbohydrates,
            'fiber'         => $request->fiber,
            'source'        => $request->source ?? 'manual',
        ]);

        return response()->json($food, 201);
    }

    /**
     * PUT /api/admin/foods/{id}
     * Update food
     */
    public function update(Request $request, $id)
{
    $food = Food::find($id);

    if (!$food) {
        return response()->json(['message' => 'Food not found'], 404);
    }

    $validated = $request->validate([
        'name'          => 'sometimes|string|max:255',
        'calories'      => 'nullable|numeric',
        'protein'       => 'nullable|numeric',
        'fat'           => 'nullable|numeric',
        'carbohydrates' => 'nullable|numeric',
        'fiber'         => 'nullable|numeric',
        'usda_fdc_id'   => 'nullable'
    ]);

    // FIX: Jangan ubah usda_fdc_id kalau request tidak mengirim atau mengirim kosong/null/string "null"
    if ($request->has('usda_fdc_id')) {
        if ($request->usda_fdc_id !== null && $request->usda_fdc_id !== "null" && $request->usda_fdc_id !== "") {
            $food->usda_fdc_id = $request->usda_fdc_id; 
        }
        // kalau null, jangan ubah field ini → biarkan nilai lama
    }

    // Update field lain
    $food->name          = $request->name          ?? $food->name;
    $food->calories      = $request->calories      ?? $food->calories;
    $food->protein       = $request->protein       ?? $food->protein;
    $food->fat           = $request->fat           ?? $food->fat;
    $food->carbohydrates = $request->carbohydrates ?? $food->carbohydrates;
    $food->fiber         = $request->fiber         ?? $food->fiber;
    $food->source        = $request->source        ?? $food->source;

    $food->save();

    return response()->json($food);
}


    /**
     * DELETE /api/admin/foods/{id}
     * Delete food - DISABLED (edit only)
     */
    public function destroy($id)
    {
        return response()->json(['message' => 'Delete functionality is disabled. Please use edit to update the food item.'], 403);
    }

    /**
     * GET /api/admin/stats
     * Get admin dashboard statistics
     */
    public function stats()
    {
        $totalFoods = Food::count();
        $totalUsers = \App\Models\User::where('role', 'calomate')->count();
        $totalAdmins = \App\Models\User::where('role', 'admin')->count();
        $totalJournalEntries = \App\Models\FoodJournal::count();

        return response()->json([
            'total_foods' => $totalFoods,
            'total_users' => $totalUsers,
            'total_admins' => $totalAdmins,
            'total_journal_entries' => $totalJournalEntries,
        ]);
    }
}
