<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AfterPresentation;
use App\Http\Controllers\BeforePresentation;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\JobTraining;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\TitleController;
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
    Route::delete('/kerja-praktik/cancel', [JobTraining::class, 'cancel']);
    Route::post('/kerja-praktik/first-submission/{id}/{userID}', [JobTraining::class, 'firstSubmission']); // admin acc pengajuan awal
    Route::post('/kerja-praktik/second-submission/{id}/{userID}', [JobTraining::class, 'secondSubmission']); // admin acc pengajuan berkas dari jurusan

    Route::post('/report', [ReportController::class, 'store']);
    Route::post('/report/{id}/{userID}', [ReportController::class, 'update']); // mengirimkan id dari report nya dan user id nya

    Route::post('/logbook', [LogbookController::class, 'store']);

    Route::get('/list-first-submission', [AdminController::class, 'firstSubmission']);
    Route::get('/list-second-submission', [AdminController::class, 'secondSubmission']);
    Route::get('/list-before-presentation', [AdminController::class, 'beforePresentation']);
    Route::get('/list-after-presentation', [AdminController::class, 'afterPresentation']);
    Route::get('/list-hardcopy', [AdminController::class, 'listHardCopy']);

    Route::post('/members', [MemberController::class, 'add']);
    Route::post('/submissions/member', [SubmissionController::class, 'memberUpload']);
    Route::post('/submissions/second', [SubmissionController::class, 'uploadSecondSubmission']);
    Route::resource('/submissions', SubmissionController::class);

    Route::get('/supervisors/list-req-title', [SupervisorController::class, 'listReqTitle']);
    Route::get('/supervisors/list-req-presentation', [SupervisorController::class, 'listReqPresentation']);
    Route::get('/supervisors/list-to-score', [SupervisorController::class, 'listToScore']);
    Route::get('/supervisors/list-report', [SupervisorController::class, 'listReport']);
    Route::resource('/supervisors', SupervisorController::class);

    Route::post('/title/{id}/{userID}', [TitleController::class, 'actionTitle']);
    Route::resource('/title', TitleController::class);

    Route::post('/before-presentation/{id}/{userID}', [BeforePresentation::class, 'actionBeforePresentation']);
    Route::resource('/before-presentation', BeforePresentation::class);
    
    Route::post('/presentation/{id}/{userID}', [Presentation::class, 'actionPresentation']);
    Route::resource('/presentation', BeforePresentation::class);

    Route::post('/already-presentation/{id}/{userID}', [AfterPresentation::class, 'actionAlreadyPresentation']);
    Route::post('/score-presentation/{id}/{userID}', [AfterPresentation::class, 'scorePresentation']);
    Route::post('/after-presentation/{id}/{userID}', [AfterPresentation::class, 'actionAfterPresentation']);
    Route::resource('/after-presentation', AfterPresentation::class);

    Route::post('/invitation', [InviteController::class, 'update']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
