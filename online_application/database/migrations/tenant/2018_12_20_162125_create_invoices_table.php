<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid');
            $table->boolean('enabled')->default(false);
            $table->integer('submission_id')->unsigned()->index()->nullable();
            $table->integer('booking_id')->unsigned()->index()->nullable();
            $table->integer('student_id')->unsigned()->index();
            $table->integer('application_id')->nullable()->unsigned()->index();
            $table->date('due_date');
            $table->float('total');
            $table->string('payment_gateway')->nullable();
            $table->text('properties')->nullable();
            $table->timestamps();

            // $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
