<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use App\Models\TweetData;
use App\Models\Question;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTweets = TweetData::count();
        $policies = Policy::withCount(['tweets as positive_count' => function($q){
            $q->where('sentiment_label', 'positive');
        }, 'tweets as negative_count' => function($q){
            $q->where('sentiment_label', 'negative');
        }, 'tweets as neutral_count' => function($q){
            $q->where('sentiment_label', 'neutral');
        }])->with(['tweets' => function($query) {
            $query->latest('posted_at'); 
        }])->get();

        return view('dashboard', compact('policies', 'totalTweets'));
    }

    public function education()
    {
        $totalTweets = TweetData::count();

        // LOGIKA 50 SOAL FIXED (40 BASIC + 10 ESSAY)
        // Kita ambil secara acak (Random Order) agar urutannya beda tiap refresh
        
        $basicQuestions = Question::where('type', 'basic')
                                  ->inRandomOrder()
                                  ->limit(40) // Pastikan ambil 40
                                  ->get();
        
        $essayQuestions = Question::where('type', 'essay')
                                  ->inRandomOrder()
                                  ->limit(10) // Pastikan ambil 10
                                  ->get();
        
        // Gabung kedua jenis soal (Total 50) lalu acak posisi urutannya
        $questions = $basicQuestions->merge($essayQuestions)->shuffle();

        // Jika data kurang dari 50 (misal baru seed awal), kode ini tetap jalan aman
        
        return view('education', compact('questions', 'totalTweets'));
    }
}