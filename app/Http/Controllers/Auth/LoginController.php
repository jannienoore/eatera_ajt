<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\UserProfile;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function register(Request $r)
    {
        $r->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
            'weight' => 'required|numeric|min:20|max:300',
            'height' => 'required|numeric|min:100|max:250',
            'diet_goal' => 'required|in:deficit,maintain,bulking',
        ]);

        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => Hash::make($r->password),
            'role' => 'calomate'
        ]);

        // Calculate target calories using Harris-Benedict formula
        $age = Carbon::parse($r->date_of_birth)->age;
        
        $bmr = ($r->gender === 'male')
            ? (10 * $r->weight) + (6.25 * $r->height) - (5 * $age) + 5
            : (10 * $r->weight) + (6.25 * $r->height) - (5 * $age) - 161;

        $adjustment = match ($r->diet_goal) {
            'deficit' => -300,
            'bulking' => 300,
            default => 0,
        };

        $target_calories = round($bmr + $adjustment);

        // Create profile with calculated target_calories
        UserProfile::create([
            'user_id' => $user->id,
            'gender' => $r->gender,
            'date_of_birth' => $r->date_of_birth,
            'weight' => $r->weight,
            'height' => $r->height,
            'diet_goal' => $r->diet_goal,
            'target_calories' => $target_calories,
        ]);

        return response()->json([
            'message' => 'Register success, please login'
        ], 201);
    }

    public function login(Request $r)
    {
        \Log::info('Login attempt', ['email' => $r->email, 'request' => $r->all()]);
        
        $r->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $r->email)->first();
        
        \Log::info('User lookup', ['email' => $r->email, 'found' => !!$user, 'user_id' => $user?->id]);

        if (!$user || !Hash::check($r->password, $user->password)) {
            \Log::warning('Login failed', ['email' => $r->email]);
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials']
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;
        
        \Log::info('Login successful', ['email' => $r->email, 'user_id' => $user->id]);

        return response()->json([
            'message' => 'Login successful',
            'user'  => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $r)
    {
        $r->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
