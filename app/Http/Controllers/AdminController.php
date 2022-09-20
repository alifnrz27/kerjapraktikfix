<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Submission;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function firstSubmission(Request $request)
    {
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $submissions = Submission::where([
            'academic_year_id' => $academic_year->id,
            'submission_status_id' => 9
        ])->get();

        return response()->json([
            'message'=> 'success',
            'data' => $submissions
        ], 200);
    }

    public function secondSubmission(Request $request)
    {
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $submissions = Submission::where([
            'academic_year_id' => $academic_year->id,
            'submission_status_id' => 12
        ])->get();

        return response()->json([
            'message'=> 'success',
            'data' => $submissions
        ], 200);
    }

    public function beforePresentation(Request $request)
    {
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $submissions = Submission::where([
            'academic_year_id' => $academic_year->id,
            'submission_status_id' => 19
        ])->get();

        return response()->json([
            'message'=> 'success',
            'data' => $submissions
        ], 200);
    }

    public function afterPresentation(Request $request)
    {
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $submissions = Submission::where([
            'academic_year_id' => $academic_year->id,
            'submission_status_id' => 26
        ])->get();

        return response()->json([
            'message'=> 'success',
            'data' => $submissions
        ], 200);
    }

    public function listHardCopy(Request $request)
    {
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        $academic_year = AcademicYear::where(['status' => 1])->first();
        $submissions = Submission::where([
            'academic_year_id' => $academic_year->id,
            'submission_status_id' => 28
        ])->get();

        return response()->json([
            'message'=> 'success',
            'data' => $submissions
        ], 200);
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
        //
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
    public function update(Request $request, $id)
    {
        //
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
}
