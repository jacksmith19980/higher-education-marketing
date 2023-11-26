<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldsTable extends Migration
{
    /**
     * Run the migrations.

     *

     * @return void
     */
    public function up()
    {
        Schema::create('fields', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('section_id')->unsigned()->index();

            $table->boolean('published')->default(true);

            $table->string('object')->default('student');

            $table->integer('repeater')->nullable();

            $table->string('label');

            $table->string('name');

            $table->string('field_type');

            $table->text('data');

            $table->text('properties');

            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.

     *

     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields');
    }
}
