<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid')->unique();
            $table->integer('envelope_id')->unsigned();
            $table->integer('student_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('submission_id')->unsigned()->nullable();
            $table->string('title');
            $table->string('status');
            $table->string('service');
            $table->text('url')->nullable();
            $table->longText('properties')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
