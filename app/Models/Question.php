<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'title', 'description', 'category','approved_by','correct_answer','no_of_views','question_type','closed_at'];
    public function options(){
        return $this->hasMany(Question_option::class);
    }
}

