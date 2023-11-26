<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agent_id')->unsigned()->index()->nullable();
            $table->integer('owner_id')->unsigned()->index()->nullable();
            $table->integer('parent_id')->unsigned()->index()->nullable();
            $table->string('role')->default('student');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email', 100)->unique();
            $table->enum('stage', ['applicant', 'student'])->default('applicant');
            $table->string('password');
            $table->string('admission_stage', 50)->nullable();
            $table->string('avatar', 500)->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
