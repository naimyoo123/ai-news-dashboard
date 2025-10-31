<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('news_articles', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2);
            $table->string('source')->nullable();
            $table->string('author')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('url')->nullable();
            $table->string('url_to_image')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
            $table->index(['country_code','published_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('news_articles'); }
};
