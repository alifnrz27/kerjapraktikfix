<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function update(Request $request){
        // jika menerima undangan
        $lastSubmission = Submission::where(['user_id' => $request->user()->id])->latest()->first();
        if($lastSubmission){
            if($request->accept_status == 1){
                if($lastSubmission->submission_status_id == 2){
                    Submission::where(['id' => $lastSubmission->id])->update(['submission_status_id'=>4]);
                }
            }
    
            // jika menolak undangan
            elseif($request->accept_status == 0){
                if($lastSubmission->submission_status_id == 2){
                    Submission::where(['id' => $lastSubmission->id])->update(['submission_status_id'=>3]);
                    User::where(['id' => $lastSubmission->user_id])->update(['inviteable' => 1]);
                }
                
            }
    
            // cek jika tidak ada lagi yg menerima undangan, ubah status ketua jadi 5 (menuggu anggota upload)
            $checkTeam = Submission::where(['team_id' => $lastSubmission->team_id, 'submission_status_id'=>2])->get();
            if(count($checkTeam) == 0){
                Submission::where([
                    'team_id' => $lastSubmission->team_id,
                    'submission_status_id' => 1
                ])->update(['submission_status_id'=>5]);
            }
    
            return response()->json([
                'message' => 'success'
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'data not found'
            ], 200);
        }
    }
}
