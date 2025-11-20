<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\TweetData;
use App\Models\Question;
use App\Services\TwitterService;
use App\Services\SentimentService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan Halaman Utama (Dashboard)
     */
    public function index()
    {
        // 1. Hitung Total Tweet untuk Footer
        $totalTweets = TweetData::count();

        // 2. Ambil Data Kebijakan + Statistik + Tweet Lengkap
        $policies = Policy::withCount(['tweets as positive_count' => function($q){
            $q->where('sentiment_label', 'positive');
        }, 'tweets as negative_count' => function($q){
            $q->where('sentiment_label', 'negative');
        }, 'tweets as neutral_count' => function($q){
            $q->where('sentiment_label', 'neutral');
        }])
        ->with(['tweets' => function($query) {
            // PENTING: Tidak pakai take() alias UNLIMITED
            // Agar semua komentar muncul di scroll area
            $query->latest('posted_at'); 
        }])
        ->get();

        return view('dashboard', compact('policies', 'totalTweets'));
    }

    /**
     * Menampilkan Halaman Edukasi (Kuis 50 Soal)
     */
    public function education()
    {
        // 1. Hitung Total Tweet untuk Footer
        $totalTweets = TweetData::count();

        // 2. LOGIKA 50 SOAL (40 Basic + 10 Essay)
        // Kita ambil secara acak agar setiap refresh soalnya beda
        
        $basicQuestions = Question::where('type', 'basic')
                                  ->inRandomOrder()
                                  ->limit(40)
                                  ->get();
        
        // Ambil type 'essay' (atau 'advanced' jika di seeder pakai istilah advanced)
        // Kita cari aman dengan cek keduanya
        $essayQuestions = Question::whereIn('type', ['essay', 'advanced'])
                                  ->inRandomOrder()
                                  ->limit(10)
                                  ->get();
        
        // Gabung kedua jenis soal lalu acak urutannya
        $questions = $basicQuestions->merge($essayQuestions)->shuffle();

        // Kirim ke view education
        return view('education', compact('questions', 'totalTweets'));
    }

    /**
     * Endpoint untuk AJAX Live Fetch (Opsional)
     */
    public function liveFetch(TwitterService $twitter, SentimentService $sentiment)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'System is monitoring via background worker.'
        ]);
    }
}