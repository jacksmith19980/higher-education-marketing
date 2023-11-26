<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key', 100);
            // TODO make a polymorphic relation with courses and programs
            // https://laravel.com/docs/6.x/eloquent-relationships#polymorphic-relationships
            $table->unsignedBigInteger('object_id');
            $table->string('object_type', 50)->nullable()->index()->comment('Type of object in the object_id relation');
            $table->string('date_type', 100)->nullable()->index();
            $table->string('properties');
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
        Schema::dropIfExists('dates');
    }
}
