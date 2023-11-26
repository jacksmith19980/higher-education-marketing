<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classroom_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('classroom_id')->nullable()->index();
            $table->unsignedBigInteger('lesson_id')->nullable()->index();
            $table->unsignedBigInteger('schedule_id')->nullable()->index();
            $table->string('day', 50);
            /*$table->time('start_time');
            $table->time('end_time');*/
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // TODO make this relation later in other migration
            //$table->foreign('classroom_id')->references('id')->on('classrooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classroom_slots');
    }
}
