<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'user_id',
        'team_id',
        'place',
        'name_leader',
        'address',
        'number',
        'start',
        'end',
        'description',
        'form_submission',
        'transcript',
        'vaccine',
        'submission_status_id',
        'form_major',
        'form_company',
        'title',
        'form_presentation',
        'result_company',
        'log_activity',
        'form_mentoring',
        'report',
        'screenshot_before_presentation',
        'statement_letter',
        'report_of_presentation',
        'notes',
        'report_revision',
        'screenshot_after_presentation',
        'date_presentation',
        'place_presentation',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
