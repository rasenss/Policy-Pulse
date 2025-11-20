<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TweetData extends Model
{
    use HasFactory;

    // KITA TAMBAHKAN INI AGAR BISA DIISI DATA
    protected $fillable = [
        'policy_id',
        'tweet_id',
        'content',
        'author_username',
        'posted_at',
        'type',
        'sentiment_score',
        'sentiment_label',
    ];

    // Relasi balik ke Policy (opsional tapi bagus untuk kerapian)
    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }
}