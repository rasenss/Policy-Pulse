<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->string('type'); // 'basic', 'advanced', 'essay'
            $table->json('options')->nullable(); // Nullable karena essay tidak punya opsi ABC
            $table->text('correct_answer'); // Untuk Essay, ini adalah "Jawaban Ideal"
            $table->json('keywords')->nullable(); // UNTUK LOGIKA ESSAY: Kata kunci wajib ada
            $table->text('explanation');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};