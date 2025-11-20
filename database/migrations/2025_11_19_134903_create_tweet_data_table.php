<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::create('tweet_data', function (Blueprint $table) {
        $table->id();
        $table->foreignId('policy_id')->constrained()->onDelete('cascade');
        $table->string('tweet_id')->unique();
        $table->text('content');
        $table->string('author_username')->nullable();
        $table->dateTime('posted_at');
        $table->string('type'); // 'reply' atau 'quote'
        // Hasil Analisis Sentimen
        $table->float('sentiment_score')->default(0); // -1 (Negatif) s/d 1 (Positif)
        $table->string('sentiment_label')->default('neutral'); // positive, negative, neutral
        $table->timestamps();
    });
}
};
