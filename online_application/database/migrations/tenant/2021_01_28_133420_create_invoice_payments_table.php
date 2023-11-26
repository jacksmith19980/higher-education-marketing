<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid', 100);
            $table->float('amount_paid');
            $table->string('status', 50);
            $table->string('payment_gateway')->nullable();
            $table->string('payment_method')->nullable();
            $table->longText('properties')->nullable();
            $table->integer('invoice_id')->unsigned()->nullable();
            $table->integer('student_id')->unsigned();
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_status');
    }
}
