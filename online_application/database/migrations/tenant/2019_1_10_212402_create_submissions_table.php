<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid', 20)->nullable();
            $table->integer('student_id')->unsigned()->index();
            $table->integer('application_id')->unsigned()->index();
            $table->integer('booking_id')->unsigned()->index()->nullable();
            $table->longText('data');
            $table->string('status')->nullable();
            $table->integer('fields_progress_status')->nullable();
            $table->integer('steps_progress_status')->nullable();
            $table->longText('properties')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->softDeletes();
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
}
