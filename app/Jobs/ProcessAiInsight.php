<?php

namespace App\Jobs;

use App\Models\AiInsight;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessAiInsight implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public AiInsight $insight) {}

    public function handle(): void
    {
        // Example: generate analysis
        $this->insight->analysis = "Processed asynchronously!";
        $this->insight->save();
    }
}
