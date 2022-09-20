<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Submission;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

    class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $academicYear = AcademicYear::where('status', 1)->first();
        $data = [
            'team_status'=> 'required',
            'transcript' =>'required',
            'form_submission' => 'required',
            'vaccine' => 'required',
            'place' => 'required',
            'name_leader' => 'required',
            'address' => 'required',
            'number' => 'required',
            'start' => 'required',
            'end' => 'required',
        ];
        // default bahwa dia tidak berkelompok
        $team_id = 0;

        // jika dia berkelompok
        if($request->team_status == 1){
            $data['members'] = 'required';
        }

        $request->validate($data);
        $submission_status = 9;

        if($request->team_status == 1){
            $members = explode(',', $request->members);
            foreach($members as $member){
                //untuk mengantisipasi, bagian akhir dari array ada nilai null
                if(!$member){
                    break;
                }
                
                $user = User::where([
                    ['role', '=',  3], //harus mahasiswa
                    ['inviteable', '=', 1], // bisa diundang
                    ['user_status_id', '=', 1], //kalau status nya masih aktif
                    ['email', '=', $member], //jika email nya ada
                    ['email', '!=', $request->user()->email], //tidak dengan email sendiri
                ])->first();

                if(!$user){
                    return response()->json([
                        'message' => 'members not found'
                    ], 404);
                }
            }

            $team = Team::create([
                'user_id' => $request->user()->id,
            ]);
            $team_id = $team->id;
            // status menjadi menunggu tim, untuk ketua
            $submission_status = 1;

            foreach($members as $member){
                //untuk mengantisipasi, bagian akhir dari array ada nilai null
                if(!$member){
                    break;
                }
                $user = User::where([
                    'email'=> $member
                ])->first();
                Submission::create([
                    'academic_year_id' => $academicYear->id,
                    'user_id' => $user->id,
                    'team_id' => $team_id,
                    'submission_status_id' =>2,
                    'place' => $request->place,
                    'name_leader' => $request->name_leader,
                    'address' => $request->address,
                    'number' => $request->number,
                    'start' => $request->start,
                    'end' => $request->end,
                ]);

                $user->where(['email'=>$member])->update([
                    'inviteable'=>0
                ]);
            }
        }

        Submission::create([
            'academic_year_id' => $academicYear->id,
            'user_id' => $request->user()->id,
            'team_id' => $team_id,
            'form_submission' => $request->form_submission,
            'transcript' => $request->transcript,
            'vaccine' => $request->vaccine,
            'submission_status_id' => $submission_status,
            'place'=>$request->place,
            'name_leader'=>$request->name_leader,
            'address'=>$request->address,
            'number'=>$request->number,
            'start'=>$request->start,
            'end'=>$request->end,
            'description'=>$request->description,
        ]);

        User::where(['email' => $request->user()->email])->update(['inviteable' => 0]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function show(Submission $submission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function edit(Submission $submission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Submission $submission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Submission  $submission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Submission $submission)
    {
        //
    }

    public function memberUpload(Request $request){
        $lastSubmission = Submission::where(['user_id' => $request->user()->id, 'submission_status_id' => 4])->latest()->first();
        $request->validate([
            'transcript' => 'required',
            'vaccine'=> 'required',
            'form_submission' => 'required',
        ]);

        if($lastSubmission){
            $lastSubmission->update([
                'transcript' => $request->transcript,
                'vaccine' => $request->vaccine,
                'form_submission' => $request->form_submission,
                'submission_status_id' => 5
            ]);

            // cek jika tidak ada lagi yg menerima undangan
            $checkTeam = Submission::where(['team_id' => $lastSubmission->team_id, 'submission_status_id'=>2])->get();
            if(count($checkTeam) == 0){
                // cek jika tidak ada lagi yg menunggu untuk upload berkas
                $checkTeam = Submission::where(['team_id' => $lastSubmission->team_id, 'submission_status_id'=>4])->get();
                if(count($checkTeam) == 0){
                    Submission::where([
                        ['team_id', '=', $lastSubmission->team_id],
                        ['submission_status_id', '!=', 3]
                    ])->update(['submission_status_id'=>9]);
                }
            }

            return response()->json([
                'message' => 'success'
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'data not found',
            ], 404);
        }
    }

    public function uploadSecondSubmission(Request $request){
        // validasi data
        $request->validate([
            'form_major' => 'required',
            'form_company'=>'required'
        ]);

        $submission = Submission::where([
            'user_id'=> $request->user()->id,
            ])->latest()->first();

            if($submission->submission_status_id != 10 && $submission->submission_status_id != 13){
                return response()->json([
                    'message' => 'data not found'
                ], 404);
            }

        // jika tidak berkelompok
        if($submission->team_id == 0){
            $submission->update([
                'form_major' => $request->form_major,
                'form_company' => $request->form_company,
                'submission_status_id' => 12
            ]);

            return response()->json([
                'message' => 'success'
            ], 200);
        }
        // jika berkelompok
        else{
            Submission::where([
                ['team_id', '=', $submission->team_id],
                ['submission_status_id' , '!=', 3]
            ])->update([
                'form_major' => $request->form_major,
                'form_company' => $request->form_company,
                'submission_status_id' => 12
            ]);
            return response()->json([
                'message' => 'success'
            ], 200);
        }
    }
}
