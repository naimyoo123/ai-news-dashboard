<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class AiAnalysisService
{
    public function analyzeNews(array $articles): array
    {
        $newsText = $this->prepareNewsText($articles);

        return [
            'summary' => $this->generateSummary($newsText),
            'category_trends' => $this->analyzeTrends($articles),
            'analysis' => $this->generateAnalysis($newsText),
        ];
    }

    private function prepareNewsText(array $articles): string
    {
        $text = "";
        foreach ($articles as $a) {
            $text .= "Title: " . ($a['title'] ?? '') . ". ";
            $text .= "Description: " . ($a['description'] ?? '') . ". ";
            $text .= "Content: " . ($a['content'] ?? '') . "\n\n";
        }
        return $text;
    }

    private function generateSummary(string $newsText): string
    {
        $sentences = array_slice(explode('. ', $newsText), 0, 3);
        return trim(implode('. ', $sentences), " \n\r\t.") . '.';
    }

    private function analyzeTrends(array $articles): array
    {
        $categories = ['politics','technology','business','health','entertainment','sports','general'];
        $trends = [];
        foreach ($categories as $c) $trends[$c] = 0;
        foreach ($articles as $a) {
            $cat = $a['category'] ?? 'general';
            if (!isset($trends[$cat])) $trends[$cat] = 0;
            $trends[$cat]++;
        }
        return $trends;
    }

    private function generateAnalysis(string $newsText): string
    {
        return "Top topics appear across multiple areas; breaking events and local updates dominate today’s headlines.";
    }
}
