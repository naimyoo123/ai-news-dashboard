<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class NewsApiService {
    private string $baseUrl = 'https://newsapi.org/v2';
    private ?string $apiKey;
    public function __construct() { $this->apiKey = config('services.newsapi.key') ?: env('NEWS_API_KEY'); }
    public function getTopHeadlines(string $country,int $pageSize=10): array {
        if(empty($this->apiKey)) { Log::warning('No NEWS_API_KEY set.'); return []; }
        try {
            $response = Http::get("{$this->baseUrl}/top-headlines",['country'=>$country,'pageSize'=>$pageSize,'apiKey'=>$this->apiKey]);
            if($response->successful()) return $response->json();
            Log::error('NewsAPI Error: '.$response->body());
            return [];
        } catch (\Exception $e) { Log::error('NewsAPI Exception: '.$e->getMessage()); return []; }
    }
}
