<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('group_id')->nullable()->index();
            $table->unsignedBigInteger('instructor_id')->nullable()->index();
            $table->unsignedBigInteger('course_id')->nullable()->index();
            $table->unsignedBigInteger('program_id')->nullable()->index();
            $table->unsignedBigInteger('module_id')->nullable()->index();
            $table->unsignedBigInteger('classroom_id')->nullable()->index();
            $table->unsignedBigInteger('classroom_slot_id')->nullable()->index();
            $table->unsignedBigInteger('lessoneable_id')->nullable();
            $table->string('lessoneable_type')->nullable();
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('properties');
            $table->timestamps();

            // TODO make this relation later in other migration
//            $table->foreign('group_id')->references('id')->on('groups');
//            $table->foreign('instructor_id')->references('id')->on('instructors');
//            $table->foreign('course_id')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
