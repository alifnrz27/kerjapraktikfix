<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class AfterPresentation extends Controller
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
            'report_of_presentation'=>'required',
            'notes'=>'required',
            'report_revision'=>'required',
            'screenshot_after_presentation'=>'required',
        ]);

        // cek data
        $submission = Submission::where([
            'user_id' => $request->user()->id,
        ])->latest()->first();

        if($submission->submission_status_id != 25 && $submission->submission_status_id != 27){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission->update([
            'submission_status_id' => 26,
            'report_of_presentation'=>$request->report_of_presentation,
            'notes'=>$request->notes,
            'report_revision'=>$request->report_revision,
            'screenshot_after_presentation'=>$request->screenshot_after_presentation,
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

    public function actionAfterPresentation(Request $request, $submissionID, $userID){
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
            'submission_status_id' => 26
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        // jika pilihan diterima
        if($request->accept_status == 1){
            $submission->update([
                'submission_status_id' => 28,
                'description' => $request->description
            ]);
        }

        // jika pilihan ditolak
        elseif($request->accept_status == 0){
            $submission->update([
                'submission_status_id' => 27,
                'description' => $request->description
            ]);
        }

        return response()->json([
            'message'=>'success'
        ], 200);
    }

    public function hardcopy(Request $request, $submissionID, $userID){
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 1){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        // cek apakah data tersebut ada
        $submission = Submission::where([
            'id' => $submissionID,
            'user_id' => $userID,
            'submission_status_id' => 28
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission->update([
            'submission_status_id' => 29,
        ]);

        return response()->json([
            'message'=>'success'
        ], 200);
    }

    public function actionAlreadyPresentation(Request $request, $submissionID, $userID){
        // cek apakah yang melakukan ini adalah dosen
        if($request->user()->role != 2){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        // cek apakah data tersebut ada
        $submission = Submission::where([
            'id' => $submissionID,
            'user_id' => $userID,
            'submission_status_id' => 23
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission->update([
            'submission_status_id' => 24,
        ]);

        return response()->json([
            'message'=>'success'
        ], 200);
    }

    public function scorePresentation(Request $request, $submissionID, $userID){
        // cek apakah yang melakukan ini adalah dosen
        if($request->user()->role != 2){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        // validasi data
        $request->validate([
            'score_presentation'=>'required',
            'score_mentoring'=>'required',
        ]);

        // cek apakah data tersebut ada
        $submission = Submission::where([
            'id' => $submissionID,
            'user_id' => $userID,
            'submission_status_id' => 24
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission->update([
            'submission_status_id' => 25,
            'score_presentation'=>$request->score_presentation,
            'score_mentoring'=>$request->score_mentoring,
        ]);

        return response()->json([
            'message'=>'success'
        ], 200);
    }
}
