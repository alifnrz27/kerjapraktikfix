<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id');
            $table->foreignId('user_id');
            $table->foreignId('supervisor_id')->nullable();
            $table->foreignId('team_id');
            $table->string('place');
            $table->string('name_leader');
            $table->string('address');
            $table->string('number');
            $table->string('start');
            $table->String('end');
            $table->string('description')->nullable();
            $table->string('form_submission')->nullable();
            $table->string('transcript')->nullable();
            $table->string('vaccine')->nullable();
            $table->string('form_major')->nullable();
            $table->string('form_company')->nullable();
            $table->string('title')->nullable();
            $table->string('form_presentation')->nullable();
            $table->string('result_company')->nullable();
            $table->string('log_activity')->nullable();
            $table->string('form_mentoring')->nullable();
            $table->string('report')->nullable();
            $table->string('screenshot_before_presentation')->nullable();
            $table->string('statement_letter')->nullable();
            $table->string('date_presentation')->nullable();
            $table->string('place_presentation')->nullable();
            $table->string('report_of_presentation')->nullable();
            $table->string('notes')->nullable();
            $table->string('report_revision')->nullable();
            $table->string('screenshot_after_presentation')->nullable();
            $table->string('score_presentation')->nullable();
            $table->string('score_mentoring')->nullable();
            $table->foreignId('submission_status_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
};
