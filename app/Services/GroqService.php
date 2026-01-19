<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqService
{
    protected string $endpoint = 'https://api.groq.com/openai/v1/chat/completions';
    protected int $timeout = 60; // seconds

    public function chat(array $messages)
    {
        try {
            $apiKey = config('services.groq.key');
            
            if (empty($apiKey)) {
                throw new \Exception('Groq API key is not configured');
            }
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])
                ->post($this->endpoint, [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 1024,
                ]);

            if ($response->failed()) {
                $errorMsg = $response->json('error.message') ?? $response->body();
                \Log::error('Groq API Response Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'message' => $errorMsg
                ]);
                throw new \Exception('Groq API Error: ' . $errorMsg);
            }

            $content = $response->json('choices.0.message.content');
            
            if (empty($content)) {
                throw new \Exception('Empty response from Groq API');
            }

            return $content;
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \Exception('Connection Error: ' . $e->getMessage());
        } catch (\Illuminate\Http\Client\RequestException $e) {
            throw new \Exception('Request Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception('Error calling Groq API: ' . $e->getMessage());
        }
    }
}
