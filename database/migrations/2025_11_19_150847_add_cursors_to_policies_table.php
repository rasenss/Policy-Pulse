<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('policies', function (Blueprint $table) {
        $table->string('next_cursor')->nullable(); // Penanda halaman API selanjutnya
        $table->string('fetch_status')->default('replies'); // Sedang ambil 'replies' atau 'quotes'
        $table->timestamp('last_updated_at')->nullable();
    });
}

public function down(): void
{
    Schema::table('policies', function (Blueprint $table) {
        $table->dropColumn(['next_cursor', 'fetch_status', 'last_updated_at']);
    });
}
};
