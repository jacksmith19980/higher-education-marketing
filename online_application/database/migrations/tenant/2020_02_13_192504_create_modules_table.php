<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
            $table->integer('course_id')->unsigned()->nullable()->index();
            $table->string('properties')->nullable();
            $table->timestamps();

            $table->unique(['title', 'course_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
