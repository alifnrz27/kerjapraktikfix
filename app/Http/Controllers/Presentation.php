<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class Presentation extends Controller
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
            'date_presentation'=>'required',
            'place_presentation'=>'required',
        ]);

        // cek data
        $submission = Submission::where([
            'user_id' => $request->user()->id,
            'submission_status_id' => 21
        ])->latest()->first();

        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission->update([
            'submission_status_id' => 26,
            'date_presentation'=>$request->date_presentation,
            'place_presentation'=>$request->place_presentation,
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

    public function actionPresentation(Request $request, $submissionID, $userID){
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
            'submission_status_id' => 22
        ])->first();
        if(!$submission){
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission->update([
            'submission_status_id' => 23,
        ]);

        return response()->json([
            'message'=>'success'
        ], 200);
    }
}
