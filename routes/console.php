<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Mendaftarkan perintah agar bisa dipanggil
Artisan::command('twitter:fetch', function () {
    $this->call(\App\Console\Commands\FetchTwitterData::class);
})->purpose('Ambil Data Twitter Real');

// Ini akan menjalankan perintah twitter:fetch setiap menit
Schedule::command('twitter:fetch')->everyFifteenMinutes();
