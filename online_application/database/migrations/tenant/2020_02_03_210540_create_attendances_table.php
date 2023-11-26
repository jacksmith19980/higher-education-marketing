<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['présent - classe', 'présent - en ligne', 'absent', 'retard'])->default('présent - classe');
            $table->unsignedBigInteger('lesson_id')->nullable()->index();
            $table->unsignedBigInteger('instructor_id')->nullable()->index();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->timestamps();

            $table->unique(['lesson_id', 'instructor_id', 'student_id']);

            // TODO make this relation later in other migration
//            $table->foreign('instructor_id')->references('id')->on('instructors');
//            $table->foreign('class_id')->references('id')->on('class');
//            $table->foreign('student_id')->references('id')->on('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
