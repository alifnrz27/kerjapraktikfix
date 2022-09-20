<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class BeforePresentation extends Controller
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
        // validasi data
        $request->validate([
            'form_presentation'=>'required',
            'result_company'=>'required',
            'log_activity'=>'required',
            'form_mentoring'=>'required',
            'report'=>'required',
            'screenshot_before_presentation'=>'required',
        ]);

        // cek data
        $submission = Submission::where([
            'user_id' => $request->user()->id,
        ])->latest()->first();

        if($submission->submission_status_id != 18 && $submission->submission_status_id != 20){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission->update([
            'submission_status_id' => 19,
            'form_presentation'=>$request->form_presentation,
            'result_company'=>$request->result_company,
            'log_activity'=>$request->log_activity,
            'form_mentoring'=>$request->form_mentoring,
            'report'=>$request->report,
            'screenshot_before_presentation'=>$request->screenshot_before_presentation,
            'statement_letter' =>$request->statement_letter,
        ]);

        return response()->json([
            'message' => 'success'
        ], 200);
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

    public function actionBeforePresentation(Request $request, $submissionID, $userID){
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
            'submission_status_id' => 19
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        // jika pilihan diterima
        if($request->accept_status == 1){
            $submission->update([
                'submission_status_id' => 21,
                'description' => $request->description
            ]);
        }

        // jika pilihan ditolak
        elseif($request->accept_status == 0){
            $submission->update([
                'submission_status_id' => 20,
                'description' => $request->description
            ]);
        }

        return response()->json([
            'message'=>'success'
        ], 200);
    }
}
