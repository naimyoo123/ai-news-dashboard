<?php
namespace App\Http\Controllers;

use App\Models\NewsArticle;
use App\Models\AiInsight;
use App\Services\NewsApiService;
use App\Services\AiAnalysisService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function __construct(
        private NewsApiService $newsApiService,
        private AiAnalysisService $aiAnalysisService
    ) {}

    public function dashboard()
    {
        return Inertia::render('Dashboard', [
            'countries' => $this->getAvailableCountries(),
            'recentInsights' => AiInsight::latest()->take(6)->get()
        ]);
    }

    public function getNews(Request $request)
    {
        $data = $request->validate([
            'country' => 'required|string|size:2',
            'force_refresh' => 'sometimes|boolean'
        ]);

        $country = $data['country'];
        $force = $data['force_refresh'] ?? false;

        $recent = AiInsight::where('country_code', $country)
                    ->where('generated_at', '>', now()->subHours(2))
                    ->first();

        if ($recent && !$force) {
            $articles = NewsArticle::where('country_code', $country)
                        ->latest('published_at')
                        ->take(20)->get();
            return response()->json([
                'insight' => $recent,
                'articles' => $articles,
                'cached' => true
            ]);
        }

        $newsData = $this->newsApiService->getTopHeadlines($country, 15);
        $articlesIn = $newsData['articles'] ?? [];

        if (empty($articlesIn)) {
            return response()->json(['error' => 'No articles found'], 404);
        }

        $saved = [];
        foreach ($articlesIn as $a) {
            $article = NewsArticle::create([
                'country_code' => $country,
                'source' => $a['source']['name'] ?? null,
                'author' => $a['author'] ?? null,
                'title' => $a['title'] ?? '',
                'description' => $a['description'] ?? '',
                'content' => $a['content'] ?? '',
                'url' => $a['url'] ?? '',
                'url_to_image' => $a['urlToImage'] ?? null,
                'published_at' => $a['publishedAt'] ?? now(),
                'category' => $this->categorizeArticle($a)
            ]);
            $saved[] = $article->toArray();
        }

        $insightData = $this->aiAnalysisService->analyzeNews($saved);

        $insight = AiInsight::create([
            'country_code' => $country,
            'summary' => $insightData['summary'],
            'category_trends' => $insightData['category_trends'],
            'analysis' => $insightData['analysis'],
            'generated_at' => now()
        ]);

        return response()->json([
            'insight' => $insight,
            'articles' => $saved,
            'cached' => false
        ]);
    }

    private function categorizeArticle(array $a): string
    {
        $title = strtolower($a['title'] ?? '');
        $map = [
            'politics' => ['election','minister','government','president','policy'],
            'technology' => ['tech','ai','software','internet','device'],
            'business' => ['market','economy','stock','finance','business'],
            'sports' => ['sport','match','tournament','olympic','goal'],
            'entertainment' => ['movie','celebrity','film','music','show'],
            'health' => ['health','covid','disease','hospital','doctor']
        ];
        foreach ($map as $cat => $words) {
            foreach ($words as $w) {
                if (str_contains($title, $w)) return $cat;
            }
        }
        return 'general';
    }

    private function getAvailableCountries(): array
    {
        return [
            ['code'=>'us','name'=>'United States'],
            ['code'=>'gb','name'=>'United Kingdom'],
            ['code'=>'ca','name'=>'Canada'],
            ['code'=>'au','name'=>'Australia'],
            ['code'=>'in','name'=>'India'],
            ['code'=>'ma','name'=>'Morocco'],
            ['code'=>'ng','name'=>'Nigeria'],
            ['code'=>'ae','name'=>'UAE'],
        ];
    }
}
