<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;

class TwitterService
{
    public function fetchReplies($tweetId)
    {
        $targetUrl = "https://twitter.com/x/status/" . $tweetId;
        $scriptPath = base_path('scraper.cjs');
        
        // Jalankan script Node
        // NOTE: Di Laravel, kita tidak bisa input 'Enter' ke proses yang berjalan di background.
        // JADI: Script JS di atas sudah saya set timeout lama di wait.
        // Anda punya waktu 30 detik untuk login sebelum dia lanjut otomatis.
        
        $result = Process::forever()->run("node \"$scriptPath\" " . $targetUrl);

        if ($result->failed()) {
            echo "   [NODE ERROR] " . substr($result->errorOutput(), 0, 200) . "...\n";
            return null;
        }

        $output = $result->output();
        
        // Cari JSON array di output
        preg_match('/\[.*\]/s', $output, $matches);
        
        if (isset($matches[0])) {
            $data = json_decode($matches[0], true);
            if (is_array($data)) {
                return ['replies' => $data];
            }
        }
        return null;
    }
    
    public function fetchQuotes($tweetId) {
        return $this->fetchReplies($tweetId);
    }
}