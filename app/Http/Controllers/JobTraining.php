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

    public function firstSubmission(Request $request, $submissionID, $userID){

        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        // validasi data
        $request->validate([
            'accept_status' => 'required',
        ]);

        // cek apakah data tersebut ada
        $submission = Submission::where([
            'id' => $submissionID,
            'user_id' => $userID,
            'submission_status_id' => 9
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        // jika pilihan diterima
        if($request->accept_status == 1){
            // jika sendiri
            if($submission->team_id == 0){
                $submission->update([
                    'submission_status_id' => 10,
                    'description' => $request->description
                ]);
            }
            // jika berkelompok, maka terima semua anggota kelompoknya
            else{
                
                $submission->update([
                    'submission_status_id' => 11,
                    'description' => $request->description
                ]);
                
                $submissions = Submission::where([
                    ['team_id', '=', $submission->team_id],
                    ['submission_status_id', '=', 9],
                    ['submission_status_id', '!=', 3],
                ])->get();

                if(count($submissions) == 0){
                    Submission::where([
                        ['team_id', '=', $submission->team_id],
                        ['submission_status_id', '=', 11],
                        ['submission_status_id', '!=', 3],
                    ])->update(['submission_status_id' => 10]);
                }
            }
        }

        // jika pilihan ditolak
        elseif($request->accept_status == 0){
            // jika sendiri
            if($submission->team_id == 0){
                $submission->update([
                    'submission_status_id' => 8,
                    'description' => $request->description
                ]);
                User::where(['id'=>$submission->user_id])->update(['inviteable'=>1]);
            }
            // jika berkelompok, maka tolak semua anggota kelompoknya
            else{
                Submission::where([
                    ['team_id', '=', $submission->team_id],
                    ['submission_status_id', '!=', 3],
                ])->update(['submission_status_id' => 8, 'description' => $request->description]);

                $submissions = Submission::where([
                    ['team_id', '=', $submission->team_id],
                    ['submission_status_id', '!=', 3],
                ])->get();

                foreach($submissions as $memberSubmission){
                    User::where(['id' => $memberSubmission->user_id])->update(['inviteable'=>1]);
                }
            }
        }

        return response()->json($submissionID);
    }

    public function secondSubmission(Request $request, $submissionID, $userID){

        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        // validasi data
        $request->validate([
            'accept_status' => 'required',
        ]);

        // cek apakah data tersebut ada
        $submission = Submission::where([
            'id' => $submissionID,
            'user_id' => $userID,
            'submission_status_id' => 12
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        // jika pilihan diterima
        if($request->accept_status == 1){
            // jika sendiri
            if($submission->team_id == 0){
                $submission->update([
                    'submission_status_id' => 14,
                    'description' => $request->description
                ]);
            }
            // jika berkelompok, maka tolak semua anggota kelompoknya
            else{
                // maka seluruh anggota kelompok datanya diubah
                Submission::where([
                    ['team_id', '=', $submission->team_id],
                    ['submission_status_id', '!=', 3],
                ])->update(['submission_status_id' => 14, 'description' => $request->description]);
            }
        }

        // jika pilihan ditolak
        elseif($request->accept_status == 0){
            // jika sendiri
            if($submission->team_id == 0){
                $submission->update([
                    'submission_status_id' => 13,
                    'description' => $request->description
                ]);
            }
            // jika berkelompok, maka tolak semua anggota kelompoknya
            else{
                Submission::where([
                    ['team_id', '=', $submission->team_id],
                    ['submission_status_id', '!=', 3],
                ])->update(['submission_status_id' => 13, 'description' => $request->description]);
            }
        }

        return response()->json([
            'message'=>'success'
        ], 200);
    }
}
