<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_quiz_stat extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'question_id', 'given_answer', 'is_correct', 'is_skipped'];
}
