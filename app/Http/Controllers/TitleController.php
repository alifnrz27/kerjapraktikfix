<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Title;
use Illuminate\Http\Request;

class TitleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
            'title' => 'required',
        ]);

        $submission = Submission::where([
            'user_id' => $request->user()->id
        ])->latest()->first();

        if($submission->submission_status_id != 15 && $submission->submission_status_id != 17){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }
        $submission->update([
            'submission_status_id' => 16,
            'title' => $request->title
        ]);

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Title  $title
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    public function actionTitle(Request $request, $submissionID, $userID){
        // cek apakah yang melakukan ini adalah admin
        if($request->user()->role != 2){
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
            'submission_status_id' => 16
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        // jika pilihan diterima
        if($request->accept_status == 1){
            $submission->update([
                'submission_status_id' => 18,
                'description' => $request->description
            ]);
        }

        // jika pilihan ditolak
        elseif($request->accept_status == 0){
            $submission->update([
                'submission_status_id' => 17,
                'description' => $request->description
            ]);
        }

        return response()->json([
            'message'=>'success'
        ], 200);
    }
}
