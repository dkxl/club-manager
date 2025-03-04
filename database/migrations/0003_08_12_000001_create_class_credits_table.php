<?php

/*
 * Used by Prospects
 * An event table for prospect tasks
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_credits', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignUlid('member_id')->references('id')->on('members');

            $table->text('sku');
            $table->integer('quantity');
            $table->decimal('payment', 6, 2);
            $table->string('payment_type');
            $table->string('order_number');

            $table->integer('credits')->default(0);
            $table->integer('debits')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('member_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_credits');
    }
}
