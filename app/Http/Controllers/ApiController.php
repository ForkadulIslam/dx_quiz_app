<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Question_option;
use App\Models\User_quiz_stat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    public function get_quiz($topics){
        $questions =  Question::with(['options'=>function($query){
            $query->select(['id', 'question_id', 'option']);
        }])->where('topics',$topics)->get();

        $questions = $questions->map(function ($question) {
            $options = $question->options->chunk(2);
            return [
                'id' => $question->id,
                'question' => $question->question,
                'options' => $options,
            ];
        });
        $given_answer = Auth::user()->user_quiz()->whereIn('question_id',$questions->pluck('id'))->orderBy('question_id','desc')->get();
        $last_given_answer = $given_answer->first();
        return response()->json([
            'question'=>$questions,
            'last_given_answer_question_id'=>$last_given_answer ? $last_given_answer->question_id:null,
        ],200);
    }
    public function submit_answer(Request $request){
        $is_correct = 'NO';
        $is_skipped = 'NO';
        if($request->option_id){
            $is_correct = Question_option::find($request->option_id)->is_correct_answer == 'YES' ? 'YES' : 'NO';
        }else{
            $is_skipped = 'YES';
        }
        Auth::user()->user_quiz()->create([
            'question_id' => $request->question_id,
            'given_answer' => $request->option_id,
            'is_correct' => $is_correct,
            'is_skipped' => $is_skipped
        ]);
        return response()->json(['status'=>'Success'],200);
    }




}
