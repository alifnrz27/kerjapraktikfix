<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Submission;
use Illuminate\Http\Request;

class ReportController extends Controller
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
            'report' => 'required',
        ]);

        $submission = Submission::where([
            'user_id' => $request->user()->id,
        ])->latest()->first();

        Report::create([
            'user_id' => $request->user()->id,
            'supervisor_id' => $submission->supervisor_id,
            'academic_year_id' => $submission->academic_year_id,
            'report'=> $request->report,
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
    public function update(Request $request, $id, $userID)
    {
        // cek apakah yang melakukan ini adalah dosen
        if($request->user()->role != 2){
            return response()->json([
                'message' => 'not allowed'
            ], 403);
        }

        // validasi data
        $request->validate([
            'description' => 'required',
        ]);

        $submission = Report::where([
            'user_id' => $userID,
            'id' => $id,
            'status' => 0
        ])->latest()->first();

        if(!$submission) {
            return response()->json([
                'message' => 'data not found'
            ], 404);
        }

        $submission->update([
            'status' => 1,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'success'
        ], 200);
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
