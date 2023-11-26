<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('promocodes.table', 'promocodes'), function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 32)->unique();
            $table->enum('type', ['flat', 'percentage'])->default('percentage')->comment('Percentage, flat, others');
            $table->double('reward', 10, 2)->nullable()
                ->comment('Number of reward that user gets (ex: 30 - can be used as 30% sale on something)');
            $table->integer('quantity')->nullable()->comment('How many times can promocode be used?');
            $table->text('data')->nullable()->comment('Any additional information to get from promocode');
            $table->boolean('is_disposable')->default(false)->comment('If promocode is one-time use only');
            $table->boolean('is_automatic')
                ->default(false)
                ->comment('Promocode can not be used by user, it apply automatically');

            $table->date('commence_at')->nullable();
            $table->date('expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('promocodes.table', 'promocodes'));
    }
}
