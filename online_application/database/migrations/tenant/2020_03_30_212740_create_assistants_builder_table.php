<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssistantsBuilderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assistants_builder', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->nullable()->unsigned()->index();
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('properties')->nullable();
            $table->string('help_title')->nullable();
            $table->string('help_content')->nullable();
            $table->text('help_logo')->nullable();
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
        Schema::dropIfExists('assistants_builder');
    }
}
