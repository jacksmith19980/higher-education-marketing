<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoiceables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid');
            $table->float('amount');
            $table->longText('title');
            $table->longText('properties')->nullable();
            $table->integer('invoice_id')->unsigned()->index();
            $table->integer('student_id')->unsigned()->index();
            $table->integer('quantity');
//            $table->integer('invoiceable_id')->unsigned()->index()->nullable();
//            $table->string('invoiceable_type', '50')->nullable();
            $table->morphs('invoiceable');
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
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
