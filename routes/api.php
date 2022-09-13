<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\JobTraining;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SubmissionController;
use App\Models\Submission;
use App\Models\User;
use Laravel\Jetstream\Contracts\CreatesTeams;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware([
    'auth:sanctum'
])->group(function () {
    Route::get('/kerja-praktik', [JobTraining::class, 'index']);
    Route::get('/kerja-praktik/cancel', [JobTraining::class, 'cancel']);

    Route::post('/members', [MemberController::class, 'add']);
    Route::post('/submissions/member', [SubmissionController::class, 'memberUpload']);
    Route::resource('/submissions', SubmissionController::class);

    Route::post('/invitation', [InviteController::class, 'update']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
