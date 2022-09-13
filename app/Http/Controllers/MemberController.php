<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function add(Request $request){
        $members = User::where([
            ['name', 'LIKE', '%'.$request->add.'%'],
            ['role', '=', 3],
            ['inviteable','=', 1],
            ['user_status_id', '=', 1],
            ['email', '!=', $request->user()->email],
        ])->get();

        return response()->json([
            'message' => 'success',
            'members' => $members,
        ], 200);
    }
}
