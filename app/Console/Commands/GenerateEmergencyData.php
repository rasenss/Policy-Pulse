<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Policy;
use App\Models\TweetData;
use Faker\Factory as Faker;

class GenerateEmergencyData extends Commphp artisan migrate:fresh --seedand
{
    // Nama perintah yang akan dipanggil di terminal
    protected $signature = 'twitter:emergency'; 
    protected $description = 'Isi data otomatis untuk presentasi (Backup Plan)';

    public function handle()
    {
        $this->info("=== MEMULAI EMERGENCY DATA GENERATOR ===");
        
        $policies = Policy::all();
        
        // Cek jika kebijakan kosong
        if($policies->count() == 0) {
            $this->error("GAGAL: Database Kebijakan Kosong (0).");
            $this->info("Solusi: Jalankan 'php artisan db:seed --class=PolicySeeder' dulu.");
            return;
        }

        $faker = Faker::create('id_ID'); 

        // Kamus Komentar Realistik
        $comments = [
            'negative' => [
                'Kebijakan ini sangat merugikan rakyat kecil!', 'Pemerintah harusnya mikir dulu.',
                'Tolak! Tidak masuk akal sama sekali.', 'Masa iya begini terus? Capek kita.',
                'Mundur aja deh kalau gak becus.', 'Hancur sudah harapan kita.',
                'Ini jelas mencederai demokrasi!', 'Parah banget, gak ada urgensinya.',
                'Demo jilid 2 harus segera digelar!', 'Bikin susah hidup orang banyak aja.'
            ],
            'positive' => [
                'Setuju, ini langkah maju.', 'Semoga pelaksanaannya diawasi.',
                'Akhirnya ada ketegasan.', 'Saya dukung demi kebaikan jangka panjang.',
                'Mantap, lanjutkan!', 'Langkah strategis untuk masa depan.'
            ],
            'neutral' => [
                'Masih memantau perkembangannya.', 'Bingung mau dukung atau tolak.',
                'Semoga ada sosialisasi yang lebih jelas.', 'Kita lihat saja nanti hasilnya.',
                'Nyimak dulu gan.'
            ]
        ];

        foreach ($policies as $policy) {
            $this->line("Mengisi data untuk: " . $policy->title);
            
            // Logika Sentimen Realistik
            if (str_contains($policy->title, 'TNI') || str_contains($policy->title, 'LPG')) {
                $ratio = ['neg' => 70, 'pos' => 10];
            } elseif (str_contains($policy->title, 'Raja Ampat')) {
                $ratio = ['neg' => 80, 'pos' => 5];
            } else {
                $ratio = ['neg' => 50, 'pos' => 25];
            }

            for ($i = 0; $i < 300; $i++) {
                $rand = rand(1, 100);
                
                if ($rand <= $ratio['neg']) {
                    $type = 'negative';
                    $score = rand(-9, -3) / 10;
                    $textBase = $faker->randomElement($comments['negative']);
                } elseif ($rand <= $ratio['neg'] + $ratio['pos']) {
                    $type = 'positive';
                    $score = rand(3, 9) / 10;
                    $textBase = $faker->randomElement($comments['positive']);
                } else {
                    $type = 'neutral';
                    $score = rand(-1, 1) / 10;
                    $textBase = $faker->randomElement($comments['neutral']);
                }

                TweetData::create([
                    'policy_id' => $policy->id,
                    'tweet_id' => $faker->numerify('19##############'),
                    'content' => $textBase . " " . $faker->sentence(3),
                    'author_username' => $faker->userName,
                    'posted_at' => $faker->dateTimeBetween('-5 months', 'now'),
                    'type' => $faker->randomElement(['reply', 'quote']),
                    'sentiment_score' => $score,
                    'sentiment_label' => $type,
                ]);
            }
        }
        $this->info("=== SELESAI! Total 1500 Data Siap ===");
    }
}