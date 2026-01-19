<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UsdaService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.usda.key');
    }

    public function searchFood(string $query)
    {
        return Http::get('https://api.nal.usda.gov/fdc/v1/foods/search', [
            'query' => $query,
            'pageSize' => 10,
            'api_key' => $this->apiKey,
        ])->json();
    }

    public function getFoodDetail(int $fdcId)
    {
        return Http::get("https://api.nal.usda.gov/fdc/v1/food/{$fdcId}", [
            'api_key' => $this->apiKey,
        ])->json();
    }
}
