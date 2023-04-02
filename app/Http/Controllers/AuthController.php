<?php

namespace App\Http\Controllers;


use App\Models\Question;
use App\Models\Question_option;
use App\Models\User;
use App\Models\User_quiz_stat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{



    public function login(Request $request){
        //return $request->all();
        $validator = Validator::make($request->all(),[
            'user_name'=>'required|string|min:4'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }
        $credentials = $request->only(['user_name']);
        //return $credentials;
        $user = User::where($credentials)->first();
        if(!$user){
            $user = User::create($credentials);
        }
        Auth::login($user);
        $user = User::where('user_name', Auth::user()->user_name)->first();
        $token = JWTAuth::fromUser($user);
        $user = Auth::user();
        $user->stats = $this->get_quiz_stats();
        return response()->json([
            'status'=>'Success',
            'user' => $user,
            'authorization'=>[
                'token'=>$token,
                'type'=>'bearer',
                'expires_in'=> auth()->factory()->getTTL()*60
            ]
        ]);
    }


    public function refresh_token(){
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function fetch_auth_info(){

        //return 1;
        if(Auth::check()){
            $user =  Auth::user();
            $user->stats = $this->get_quiz_stats();
            return response()->json($user,200);

        }else{
            return response()->json([
                'status'=>'Authentication failed'
            ],400);
        }
    }
    public function get_quiz_stats(){

        $quiz_stats = [
            'php' =>[
                'score'=>User_quiz_stat::where('user_id',auth()->user()->id)->where('is_correct','YES')->whereIn('question_id',Question::where('topics','PHP')->pluck('id'))->count(),
                'skip'=>User_quiz_stat::where('user_id',auth()->user()->id)->where('is_skipped','YES')->whereIn('question_id',Question::where('topics','PHP')->pluck('id'))->count(),
            ],
            'ajax' => [
                'score'=>User_quiz_stat::where('user_id',auth()->user()->id)->where('is_correct','YES')->whereIn('question_id',Question::where('topics','AJAX')->pluck('id'))->count(),
                'skip'=>User_quiz_stat::where('user_id',auth()->user()->id)->where('is_skipped','YES')->whereIn('question_id',Question::where('topics','AJAX')->pluck('id'))->count()
            ],
            'jquery' => [
                'score'=>User_quiz_stat::where('user_id',auth()->user()->id)->where('is_correct','YES')->whereIn('question_id', Question::where('topics','JQUERY')->pluck('id'))->count(),
                'skip'=>User_quiz_stat::where('user_id',auth()->user()->id)->where('is_skipped','YES')->whereIn('question_id', Question::where('topics','JQUERY')->pluck('id'))->count()
            ],
            'html' => [
                'score'=>User_quiz_stat::where('user_id',auth()->user()->id)->where('is_correct','YES')->whereIn('question_id', Question::where('topics','HTML')->pluck('id'))->count(),
                'skip'=>User_quiz_stat::where('user_id',auth()->user()->id)->where('is_skipped','YES')->whereIn('question_id', Question::where('topics','HTML')->pluck('id'))->count()
            ]
        ];
        return $quiz_stats;
    }
    public function generate_option_for_each_question(){
        Question::each(function($item){
            $rand = rand(1,4);
            for($i=1; $i<=4; $i++ ){
                Question_option::create([
                    'question_id'=>$item->id,
                    'option' => 'Option '.$i,
                    'is_correct_answer'=> $rand == $i ?? 'YES'
                ]);
            }
        });
    }


}

