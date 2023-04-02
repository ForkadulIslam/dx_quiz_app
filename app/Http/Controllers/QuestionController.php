<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Policy;
use App\Models\Question;
use App\Models\Tagged_user;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $question =  Question::with(['user','tagged_user','comments'])->orderBy('id','desc');
        if(!auth()->check()){
            $question->where('question_type','Public');
        }
        return response()->json([
            'question'=>$question->paginate(20)
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tagged_ids = array_unique(array_map(function($item) { return $item['id']; }, $request->tagged_ids));
        //return $tagged_ids;
        $question_data = $request->all();
        $question_data['user_id'] = auth()->user()->id;
        $question_data['closed_at'] = Carbon::parse($question_data['closed_at'])->timezone('Asia/Dhaka')->format('Y-m-d');
        //return $question_data;
        $question = Question::create($question_data);
        foreach($tagged_ids as $item){
            Tagged_user::create([
                'question_id' =>$question->id,
                'user_id' => $item
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::with(['user','tagged_user','tagged_user.user','comments','comments.user'])->find($id);
        $question->no_of_views = $question->no_of_views+1;
        $question->save();
        $question['is_tagged'] = in_array(auth()->user()->id,$question->tagged_user->pluck('user_id')->toArray());
        $related_question = Question::where('category',$question->category)->orderBy('id','desc')->orderBy('title','desc')->limit(5)->get();

        return response()->json([
            'question'=>$question,
            'related_question'=>$related_question
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tagged_ids = array_unique(array_map(function($item) { return $item['id']; }, $request->tagged_ids));
        //return $tagged_ids;
        $question_data = $request->all();
        //return $question_data;
        $question_data['user_id'] = auth()->user()->id;
        $question_data['closed_at'] = Carbon::parse($question_data['closed_at'])->timezone('Asia/Dhaka')->addDay(3)->format('Y-m-d');
        //return $question_data;
        Question::find($id)->fill($question_data)->save();
        $question = Question::find($id);
        $question->tagged_user()->delete();
        //return $tagged_ids;
        foreach($tagged_ids as $item){
            Tagged_user::create([
                'question_id' =>$question->id,
                'user_id' => $item
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function tag_people(){
        $user = [];
        if(request()->question){
            $ids_need_to_fuck_off = Question::find(request()->question)->tagged_user->pluck('user_id');
            //return $ids_need_to_fuck_off;
            $user = User::where('name','like','%'.request()->str.'%')->whereNotIn('id',$ids_need_to_fuck_off)->limit(20)->get();
        }else{
            $user = User::where('name','like','%'.request()->str.'%')->limit(20)->get();
        }
        $user->each(function($item){
            $item->name= $item->name . ' - '.$item->department;
        });
        return $user;
    }
    public function my_question(){
        return Question::with(['tagged_user','comments'])->where('user_id',auth()->user()->id)->orderBy('id','desc')->get();
    }
    public function reply(Request $request,$question_id){
        //return $request->all();
        $question = Question::find($question_id);
        if(!in_array(auth()->user()->id,$question->tagged_user->pluck('user_id')->toArray()) && auth()->user()->id != $question->user_id){
            return response()->json([
                'status'=>'Error',
                'message'=>'Unauthorized'
            ],400);
        }
        foreach($request->tagged_user as $item){
            Tagged_user::create([
                'question_id' =>$question_id,
                'user_id' => $item['id']
            ]);
        }
        $comment = Comment::create([
            'comment'=>$request->comment,
            'user_id'=>auth()->user()->id,
            'question_id'=>$question_id
        ]);
        return response()->json($comment,200);
    }
    public function best_answer($question_id){
        $question = $this->_remove_correct_answer($question_id);
        $question->correct_answer = request()->correct_answer;
        $question->save();
        foreach(request()->answer_ids as $id){
            $question->comments()->find($id)->fill([
                'status'=> 'ACCEPTED'
            ])->save();
        }
        $question = Question::with(['tagged_user','tagged_user.user','comments','comments.user'])->find($id);
        $question['is_tagged'] = in_array(auth()->user()->id,$question->tagged_user->pluck('user_id')->toArray());
        return response()->json([
            'question'=>$question
        ]);
    }
    public function _remove_correct_answer($question_id){
        $question = Question::find($question_id);
        $question->correct_answer = null;
        $question->save();
        //return $question->comments;
        $question->comments()->where('status','ACCEPTED')->update(['status'=>'']);
        return $question;
    }
    public function remove_correct_answer($question_id){
        $question = $this->_remove_correct_answer($question_id);
        return $question;
    }
    public function search(){
        $search_str = request()->search_str;
        $question =  Question::with(['user', 'tagged_user','comments'])->where(function($query) use ($search_str){
            $query->where('title','LIKE','%'.$search_str.'%')
                ->orWhere('description','like','%'.$search_str.'%');
        })->orderBy('id','desc')->get();
        $policies = Policy::where(function($query) use ($search_str){
           $query->where('title','LIKE','%'.$search_str.'%')
               ->orWhere('content','LIKE','%'.$search_str.'%')
               ->orWhere('file_name','LIKE','%'.$search_str.'%');
        })->orderBy('id','desc')->get();
        $merged = $question->merge($policies);
        $merged->each(function($item){
           $item->content = Str::limit($item->content,200);
           $item->description = Str::limit($item->description,200, ' (...)');
        });
        return $merged;
    }
    public function global_stats(){
        return [
            'total_question'=>Question::count(),
            'total_answer'=>Comment::count(),
            'recent_questions'=>Question::orderBy('id','desc')->where('question_type','Public')->take(3)->get(),
            'height_point_users' => Comment::with(['user'])
                ->where('status','ACCEPTED')
                ->select('user_id','status', DB::raw('count(*) as total'))
                ->groupBy('user_id')->orderBy('total','desc')->get()
        ];
    }
    public function user_question($user_id){
        return response()->json([
             'questions'=>Question::with(['tagged_user','comments'])->where('user_id',$user_id)->orderBy('id','desc')->get(),
        ],200);
    }
    public function user_answers($user_id){
        $answers = Comment::with(['question'=>function($query){
            $query->where('question_type','Public');
        },'question.user','user'])->where('user_id',$user_id)->latest()->paginate(20);
        return response()->json($answers,200);
    }
    public function remove_me_from_tag($tag_row_id){
        Tagged_user::find($tag_row_id)->delete();
        return response()->json('Deleted',200);
    }

}
