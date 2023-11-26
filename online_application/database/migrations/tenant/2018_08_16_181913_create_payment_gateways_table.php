<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');
            $table->integer('section_id')->nullable();
            $table->integer('field_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('properties')->nullable();
            $table->timestamps();

            //$table->foreign('application_id')->references('id')->on('applications');
            //$table->foreign('section_id')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
}
