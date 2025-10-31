<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('ai_insights', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2);
            $table->text('summary');
            $table->json('category_trends')->nullable();
            $table->text('analysis')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
            $table->index(['country_code','generated_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('ai_insights'); }
};
