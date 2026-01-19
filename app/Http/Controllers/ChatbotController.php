<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GroqService;
use App\Services\AhpRecommendationService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected GroqService $llm;
    protected AhpRecommendationService $ahp;

    public function __construct(
        GroqService $llm,
        AhpRecommendationService $ahp
    ) {
        $this->llm = $llm;
        $this->ahp = $ahp;
    }

    public function chat(Request $request)
    {
        try {
            // ================= VALIDASI =================
            $request->validate([
                'message' => 'required|string|max:2000',
            ]);

            $user = $request->user();
            $profile = $user->profile;

            // ================= AMBIL REKOMENDASI MENU =================
            $foods = $this->ahp->recommend($user);

            $foodList = $foods->isEmpty()
                ? 'Tidak ada rekomendasi karena sisa kalori harian sudah hampir terpenuhi.'
                : $foods->map(fn ($f) =>
                    "{$f->name} ({$f->calories} kcal, protein {$f->protein}g)"
                )->implode(", ");

            // ================= PROMPT KHUSUS EATERA =================
            $messages = [
                [
                    'role' => 'system',
                    'content' => "Kamu adalah Calora, asisten nutrisi aplikasi EATERA.

Profil user:
- Diet goal: {$profile->diet_goal}
- Target kalori harian: {$profile->target_calories} kcal

Rekomendasi menu hari ini:
{$foodList}

Aturan jawaban:
- Jawaban harus relevan dengan diet dan kalori
- Gunakan rekomendasi menu jika sesuai
- Jangan mengarang data di luar sistem
- Jawaban singkat, jelas, dan ramah"
                ],
                [
                    'role' => 'user',
                    'content' => $request->message
                ]
            ];

            // ================= PANGGIL GROQ (FREE & FAST) =================
            $reply = $this->llm->chat($messages);

            return response()->json([
                'reply' => $reply,
                'recommendations' => $foods
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Chatbot error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
