<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserProfile;
use Carbon\Carbon;

class UserProfileController extends Controller
{
    public function show(Request $request)
    {
        $profile = $request->user()->profile;
        return response()->json([
            'user' => $request->user(),
            ...$profile->toArray()
        ]);
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'weight' => 'required|numeric|min:20|max:300',
        'height' => 'required|numeric|min:100|max:250',
        'diet_goal' => 'required|in:deficit,maintain,bulking',
    ]);

    $profile = $request->user()->profile;

    $age = Carbon::parse($profile->date_of_birth)->age;

    $bmr = ($profile->gender === 'male')
        ? (10 * $data['weight']) + (6.25 * $data['height']) - (5 * $age) + 5
        : (10 * $data['weight']) + (6.25 * $data['height']) - (5 * $age) - 161;

    $adjustment = match ($data['diet_goal']) {
        'deficit' => -300,
        'bulking' => 300,
        default => 0,
    };

    $data['target_calories'] = round($bmr + $adjustment);

    $profile->update($data);

    return response()->json($profile);
    }
}

