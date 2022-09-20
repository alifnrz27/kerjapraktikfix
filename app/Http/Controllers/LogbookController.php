<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\Submission;
use Illuminate\Http\Request;

class LogbookController extends Controller
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
        $rules = [
            'date' => 'required',
            'description' => 'required',
        ];
        $request->validate($rules);

        $submission = Submission::where('user_id', $request->user()->id)->latest()->first();

        $logbooks = Logbook::where(['user_id' => $request->user()->id, 'submission_id' => $submission->id])->get();
        if (count($logbooks) > 0){
            $countLogbook = count($logbooks);
            $lastLogbook = $logbooks[$countLogbook-1];
        }

        $inputDate = strtotime($request->date);
        $startDate = strtotime($submission->start);
        $endDate = strtotime($submission->end);
        $now = strtotime('now +7 hours');
        
        // jika tanggal di luar rentang waktu yang ditentukan
        if ($inputDate < $startDate || $inputDate > $endDate ){
            return response()->json([
                'message' => 'out of range'
            ]);// tanggal yang diinput di luar rentang
        }

        // jika user memasukkan tanggal yang belum dilalui
        if($inputDate > $now){
            return response()->json([
                'message' => 'can\'t fill the logbook today'
            ]); // selesaikan harimu, baru input data
        }

        // jika user belum memasukkan data sebelum hari ini
        if(count($logbooks) == 0){
            // jika user belum memasukkan sama sekali logbook, namun sudah lompat ke tanggal lain
            if($inputDate >= strtotime($submission->start . ' +1 day')){
                return response()->json([
                    'message' => 'fill your first logbook'
                ]); // kamu belum isi logbook hari pertama
            }
        }
        else{
            // jika user memasukkan tanggal, namun hari sebelumnya belum diinputkan
            if($inputDate > strtotime($lastLogbook->date . ' +1 day')){
                return response()->json([
                    'message' => 'fill your previous day'
                ]);// kamu belum isi logbook sebelumnya
            }
        }

        // jika user memasukkan logbook di tanggal yang sudah diinput
        foreach($logbooks as $logbook){
            if($logbook->date == $request->date){
                return response()->json([
                    'message'=>'you\'re already filled logbook today'
                ]); //kamu udah isi tcuy
            }
        }

        // isi tabel logbook
        Logbook::create([
            'user_id'=>$request->user()->id,
            'submission_id' => $submission->id,
            'date'=>$request->date,
            'description'=>$request->description
        ]);
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
