<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['question_text', 'type', 'options', 'correct_answer', 'keywords', 'explanation'];

    protected $casts = [
        'options' => 'array',
        'keywords' => 'array', // Penting untuk Essay
    ];
}