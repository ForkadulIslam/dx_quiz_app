<?php

namespace App\Http\Controllers;

use App\Models\Issue_detail;
use App\Models\Meeting_issue;
use App\Models\Mom;
use App\Models\Mom_present_member;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Collection;
use function Symfony\Component\String\u;

class MomController extends Controller
{
    public function index(){
        $mom = Mom::with(['creator', 'present_members','present_members.user','meeting_actions'])->latest()->paginate(30);
        return response()->json($mom,200);
    }
    public function my_mom(){
        $mom = Mom::with(['creator', 'present_members','present_members.user','meeting_actions'])->where('created_by',auth()->user()->id)->latest()->paginate(30);
        return response()->json($mom,200);
    }
    public function user_mom($id){
        $user_mom_ids = Mom_present_member::where('user_id',$id)->pluck('mom_id');
        $mom = Mom::with(['creator', 'present_members','present_members.user','meeting_actions','meeting_actions.issue_details'=>function($query) use($id){
            return $query->where('remarks_by',$id);
        }])->whereIn('id',$user_mom_ids)->latest()->paginate(30);
        $mom->each(function($item){
            $item->action_taken_by_user = 'Completed';
            $item->meeting_actions->each(function($meeting_action) use($item){
              $meeting_action->issue_details->each(function($issue_detail) use($item) {
                  if($issue_detail->action_taken_by_user == 'Pending'){
                      $item->action_taken_by_user =  'Pending';
                  }
              });
            });
        });

        return response()->json($mom,200);
    }
    public function store(Request $request){
        $inputs = json_decode($request->input('input_data'),true);
        //return $inputs;
        $inputs['date_time'] = Carbon::parse($request->date_time)->timezone('Asia/Dhaka')->format('Y-m-d h:i:s');
        $inputs['created_by'] = Auth::user()->id;
        $inputs['reference_no'] = Carbon::now()->format('Ymd/his');
        $mom = Mom::create($inputs);
        foreach($inputs['present_members']['selected'] as $selected_member){
            $mom->present_members()->create([
                'user_id'=>$selected_member['id']
            ]);
        }
        foreach($inputs['absent_members']['selected'] as $selected_member){
            $mom->absent_members()->create([
                'user_id'=>$selected_member['id']
            ]);
        }
        foreach($inputs['meeting_actions'] as $meeting_action){

            $issue = $mom->meeting_actions()->create([
                'title'=>$meeting_action['title']
            ]);
            foreach($meeting_action['issue_details'] as $issue_detail){
                $issue->issue_details()->create([
                    'department' => $issue_detail['department'],
                    'remarks' => $issue_detail['remarks'],
                    'date'=> Carbon::parse($issue_detail['date'])->timezone('Asia/Dhaka')->format('Y-m-d'),
                    'remarks_by'=>$issue_detail['remarks_by']['id'],
                ]);
                $user = User::find($issue_detail['remarks_by']['id']);
                if($user){
                    $emailaddress = $user->email;
                    $subject = 'New Task Assigned, Check your meeting minutes';
                    $message = '<h4>MEETING REF NO: '.$mom->reference_no.'</h4> <br> TASK:'.$issue_detail['remarks'];
                    $target_url = 'https://vos.vlmbd.com/memail/send?emailaddress='.$emailaddress.'&subject='.$subject.'&message='.$message;
                    $client = new Client();
                    $client->get($target_url);
                }
            }
        }
        if(request()->file('files')){
            foreach (request()->file('files') as $file){
                $fileName = time().'.'.$file->extension();
                if($file->move(public_path('uploads'), $fileName)){
                    $mom->attachments()->create([
                        'attachment'=>$fileName
                    ]);
                }
            }
        }
        $data = Mom::with(['present_members','absent_members','meeting_actions','meeting_actions.issue_details','attachments'])->find($mom->id);
        return response()->json($data,200);
    }
    public function show($id){
        $mom = Mom::with(['present_members','present_members.user','absent_members','absent_members.user','meeting_actions','meeting_actions.issue_details','meeting_actions.issue_details.user','creator','attachments'])->find($id);
        return $mom;
    }
    public function mom_pdf($id){
        $mom = Mom::with(['present_members','present_members.user','absent_members','absent_members.user','meeting_actions','meeting_actions.issue_details','meeting_actions.issue_details.user','creator','attachments'])->find($id);
        //return $mom;
        return view('mom_pdf',compact('mom'));
    }
    public function update(Request $request,$id){
        //return public_path('uploads/'.'1675851648.png');
        $inputs = json_decode($request->input_data,true);
        $inputs['date_time'] = Carbon::parse($request->date_time)->timezone('Asia/Dhaka')->format('Y-m-d h:i:s');
        $inputs['created_by'] = Auth::user()->id;
        $mom = Mom::find($id);
        $mom->fill($inputs)->save();
        $mom->present_members()->delete();
        foreach($inputs['present_members']['selected'] as $selected_member){
            $mom->present_members()->create([
                'user_id'=>$selected_member['id']
            ]);
        }
        $mom->absent_members()->delete();
        foreach($inputs['absent_members']['selected'] as $selected_member){
            $mom->absent_members()->create([
                'user_id'=>$selected_member['id']
            ]);
        }
        //return $inputs;
        $mom->meeting_actions()->delete();
        foreach($inputs['meeting_actions'] as $meeting_action){
            $issue = $mom->meeting_actions()->create([
                'title'=>$meeting_action['title']
            ]);
            foreach($meeting_action['issue_details'] as $issue_detail){
                $issue->issue_details()->create([
                    'department' => $issue_detail['department'],
                    'remarks' => $issue_detail['remarks'],
                    'date'=> Carbon::parse($issue_detail['date'])->timezone('Asia/Dhaka')->format('Y-m-d'),
                    'remarks_by'=>$issue_detail['remarks_by']['id'],
                ]);
                $user = User::find($issue_detail['remarks_by']['id']);
                if($user){
                    $emailaddress = $user->email;
                    $subject = 'Task has been updated, Check your meeting minutes';
                    $message = '<h4>MEETING REF NO: '.$mom->reference_no.'</h4> <br> UPDATED TASK:'.$issue_detail['remarks'].'';
                    $target_url = 'https://vos.vlmbd.com/memail/send?emailaddress='.$emailaddress.'&subject='.$subject.'&message='.$message;
                    $client = new Client();
                    $client->get($target_url);
                }
            }
        }
        $mom->attachments()->whereNotIn('id',collect($inputs['previous_attachments'])->pluck('id'))->get()->each(function($attachment){
            $attachment->delete();
        });
        if(is_array(request()->file('files'))){
            foreach (request()->file('files') as $file){
                $fileName = time().'.'.$file->extension();
                if($file->move(public_path('uploads'), $fileName)){
                    $mom->attachments()->create([
                        'attachment'=>$fileName
                    ]);
                }
            }
        }
        $data = Mom::with(['present_members','absent_members','meeting_actions','meeting_actions.issue_details'])->find($mom->id);
        return response()->json($data,200);
    }
    public function action_taken_by_user(Request $request){
        $issue_details = Issue_detail::find($request->issue_details_id)->fill(['action_taken_by_user'=>$request->action_taken_by_user])->save();
        return response()->json($issue_details,200);
    }

    public function getCategory(){
        return User::groupBy('department')->select('department')->pluck('department')->toArray();
    }

}
