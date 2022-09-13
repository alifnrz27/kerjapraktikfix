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
    ];
}
