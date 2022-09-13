<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class JobTraining extends Controller
{
    public function index(Request $request){
        $user = User::where([
            'email' => $request->user()->email,
        ])->first();

        $data['role'] = $user->role;
        $data['lastSubmissionStatus'] = 0;
        if($user->role == 3){
            $lastSubmission = Submission::where(['user_id'=>$user->id])->latest()->first();
            if($lastSubmission){
                $data['lastSubmissionStatus'] = $lastSubmission->submission_status_id;
            }
        }
        return response()->json([
                'message' => 'success',
                'data' => $data
                ], 200);
    }

    public function cancel(Request $request){
        $lastSubmission = Submission::where([
            'user_id' => $request->user()->id,
        ])->latest()->first();

        // jika data ada
        if($lastSubmission){

            // jika dia berkelompok
            if($lastSubmission->team_id != 0){
                Submission::where([
                    ['team_id', '=', $lastSubmission->team_id],
                    ['submission_status_id', '!=', 3]
                ])->update(['submission_status_id' =>7]);
    
                $members = Submission::where([
                    ['team_id', '=', $lastSubmission->team_id],
                    ['submission_status_id', '!=', 3]
                ])->get();
    
                foreach($members as $member){
                    User::where(['id' => $member->user_id])->update(['inviteable' => 1]);
                }
            }
            else{
                Submission::where([
                    ['id', '=', $lastSubmission->id]
                ])->update(['submission_status_id' =>6]);
            }
        }else{
            return response()->json([
                'message' => 'data not found'
                ], 404);
        }
    }
}
