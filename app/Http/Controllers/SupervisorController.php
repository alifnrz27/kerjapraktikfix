<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Report;
use App\Models\Submission;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $supervisors = User::where([
            'role' => 2,
            'user_status_id' => 1,
        ])->get();

        $students = Submission::where(['submission_status_id'=>14])->with(['user'])->get();

        return response()->json([
            'message' => 'success',
            'supervisors' => $supervisors,
            'students' => $students
        ], 200);
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
    public function store()
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $student)
    {
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        // validasi data
        $request->validate([
            'supervisor' => 'required',
        ]);

        $student = User::where([
            'email' => $student,
            'role'=>3,
            'user_status_id' =>1,
            'inviteable' =>0
        ])->first();

        $supervisor = User::where([
            'email' => $request->supervisor,
            'role'=>2,
            'user_status_id' =>1,
        ])->first();

        if(!$student || !$supervisor){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission = Submission::where([
            'user_id' => $student->id,
            'submission_status_id' => 14
        ])->latest()->first();

        $submission->update([
            'submission_status_id' => 15,
            'supervisor_id' => $supervisor->id
        ]);

        return response()->json([
            'message' => 'success'
        ],200);
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

    public function listReqTitle(Request $request)
    {
        // cek apakah yang melakukan ini adalah dosen
        if($request->user()->role != 2){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $submissions = Submission::where([
            'supervisor_id' => $request->user()->id,
            'academic_year_id' => $academic_year->id,
            'submission_status_id' => 16
        ])->get();

        return response()->json([
            'message'=> 'success',
            'data' => $submissions
        ], 200);
    }

    public function listReqPresentation(Request $request)
    {
        // cek apakah yang melakukan ini adalah dosen
        if($request->user()->role != 2){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $submissions = Submission::where([
            'supervisor_id' => $request->user()->id,
            'academic_year_id' => $academic_year->id,
            'submission_status_id' => 22
        ])->get();

        return response()->json([
            'message'=> 'success',
            'data' => $submissions
        ], 200);
    }

    public function listToScore(Request $request)
    {
        // cek apakah yang melakukan ini adalah dosen
        if($request->user()->role != 2){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $submissions = Submission::where([
            'supervisor_id' => $request->user()->id,
            'academic_year_id' => $academic_year->id,
            'submission_status_id' => 24
        ])->get();

        return response()->json([
            'message'=> 'success',
            'data' => $submissions
        ], 200);
    }

    public function listReport(Request $request)
    {
        // cek apakah yang melakukan ini adalah dosen
        if($request->user()->role != 2){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $reports = Report::where([
            'supervisor_id' => $request->user()->id,
            'academic_year_id' => $academic_year->id,
        ])->with('user')->get();

        return response()->json([
            'message'=> 'success',
            'data' => $reports
        ], 200);
    }
}
