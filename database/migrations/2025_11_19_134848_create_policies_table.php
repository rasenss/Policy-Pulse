<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::create('policies', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description');
        $table->string('target_tweet_id'); // ID Tweet viral yang akan diambil reply/quote-nya
        $table->string('category'); // Ekonomi, Politik, dsb
        $table->date('date_issued');
        $table->timestamps();
    });
}
};
