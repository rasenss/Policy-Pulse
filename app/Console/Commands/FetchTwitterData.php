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
    protected $description = 'Scrape Data Real via Puppeteer (Turbo Mode)';

    public function handle(TwitterService $twitter, SentimentService $sentiment)
    {
        $policies = Policy::all();
        $this->info("=== MEMULAI SCRAPING MASIF (PUPPETEER TURBO) ===");
        $this->warn("Jendela Chrome akan terbuka dan scroll otomatis.");
        $this->warn("Proses ini akan memakan waktu (3-5 menit per kebijakan). Mohon bersabar.");

        foreach ($policies as $index => $policy) {
            $this->info("\n[" . ($index + 1) . "/5] Memproses: {$policy->title}");
            $this->line("      Sedang scroll & ambil data... (Jangan tutup Chrome)");
            
            // Ambil Data
            $response = $twitter->fetchReplies($policy->target_tweet_id);
            
            if (!$response || empty($response['replies'])) {
                $this->error("      Gagal/Sedikit data ditemukan.");
                continue;
            }

            $tweets = $response['replies'];
            $count = 0;
            $duplicate = 0;

            foreach ($tweets as $tweet) {
                // Mapping Data
                $text = $tweet['text'];
                $username = str_replace('@', '', $tweet['user']['screen_name']);
                
                // Buat Hash ID unik
                $tweetId = md5($tweet['content'] ?? $text . $policy->id); 

                if (TweetData::where('tweet_id', $tweetId)->exists()) {
                    $duplicate++;
                    continue;
                }

                $analysis = $sentiment->analyze($text);

                TweetData::create([
                    'policy_id' => $policy->id,
                    'tweet_id' => $tweetId,
                    'content' => $text,
                    'author_username' => $username,
                    'posted_at' => date('Y-m-d H:i:s', strtotime($tweet['created_at'])),
                    'type' => 'reply',
                    'sentiment_score' => $analysis['score'],
                    'sentiment_label' => $analysis['label'],
                ]);
                $count++;
            }
            
            $this->info("      ✅ SUKSES: $count Tweet Baru ($duplicate duplikat dibuang).");
            $this->line("      Istirahat 5 detik sebelum lanjut...");
            sleep(5); 
        }
        
        $this->info("\n=== SELESAI ===");
    }
}