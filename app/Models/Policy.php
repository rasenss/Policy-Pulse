<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
    'title', 'description', 'target_tweet_id', 'category', 'date_issued', 'source_link',
    'next_cursor', 'fetch_status', 'last_updated_at' 
    ];

    // Relasi ke TweetData
    public function tweets()
    {
        return $this->hasMany(TweetData::class);
    }
}