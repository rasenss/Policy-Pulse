<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Policy;
use App\Models\TweetData;
use App\Services\TwitterService;
use App\Services\SentimentService;

class FetchTwitterData extends Command
{
    protected $signature = 'twitter:fetch';
    protected $description = 'Scrape Data Real via Puppeteer (Optimized Batch Insert)';

    public function handle(TwitterService $twitter, SentimentService $sentiment)
    {
        $policies = Policy::all();
        $this->info("=== MEMULAI SCRAPING (SUPABASE OPTIMIZED) ===");
        $this->warn("Jendela Chrome akan terbuka. Mohon login manual jika diminta.");

        foreach ($policies as $index => $policy) {
            $this->info("\n[" . ($index + 1) . "/5] Memproses: {$policy->title}");
            $this->line("      Sedang scroll & ambil data...");
            
            // 1. Ambil Data Mentah dari Puppeteer
            $response = $twitter->fetchReplies($policy->target_tweet_id);
            
            if (!$response || empty($response['replies'])) {
                $this->error("      Gagal/Sedikit data ditemukan.");
                continue;
            }

            $tweets = $response['replies'];
            $totalFetched = count($tweets);
            $this->info("      Dapat {$totalFetched} data mentah. Memproses...");

            // 2. Ambil semua ID yang sudah ada di Database (Sekali tarik biar cepat)
            // Tujuannya agar kita tidak perlu cek DB satu per satu
            $existingIds = TweetData::where('policy_id', $policy->id)
                                    ->pluck('tweet_id')
                                    ->toArray();
            
            $newRecords = [];
            $count = 0;

            // 3. Proses Data di Memori Laptop (Cepat)
            foreach ($tweets as $tweet) {
                $text = $tweet['text'];
                $tweetId = md5($tweet['content'] ?? $text . $policy->id); // Hash ID

                // Cek Duplikat di Memori (Bukan di DB)
                if (in_array($tweetId, $existingIds)) continue;

                // Cek Duplikat di Batch yang sedang dibuat (Double check)
                if (isset($newRecords[$tweetId])) continue;

                $username = str_replace('@', '', $tweet['user']['screen_name']);
                $analysis = $sentiment->analyze($text);

                // Masukkan ke antrean (Array)
                $newRecords[$tweetId] = [
                    'policy_id' => $policy->id,
                    'tweet_id' => $tweetId,
                    'content' => $text,
                    'author_username' => $username,
                    'posted_at' => date('Y-m-d H:i:s', strtotime($tweet['created_at'])),
                    'type' => 'reply',
                    'sentiment_score' => $analysis['score'],
                    'sentiment_label' => $analysis['label'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                $count++;
                
                // Update list existing sementara biar gak duplikat antar batch
                $existingIds[] = $tweetId;
            }

            // 4. KIRIM KE SUPABASE SEKALIGUS (Batch Insert)
            // Kita pecah per 500 data biar tidak overload
            if (!empty($newRecords)) {
                $chunks = array_chunk($newRecords, 500);
                foreach ($chunks as $chunk) {
                    TweetData::insert($chunk); // Satu kali kirim = 500 data masuk
                    $this->line("      -> Mengirim paket data ke Cloud...");
                }
                $this->info("      ✅ SUKSES: $count Tweet Baru Tersimpan.");
            } else {
                $this->line("      Tidak ada data baru (Semua duplikat).");
            }
            
            $this->line("      Istirahat 3 detik...");
            sleep(3); 
        }
        
        $this->info("\n=== SELESAI ===");
    }
}