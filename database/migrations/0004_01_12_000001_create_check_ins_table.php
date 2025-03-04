<?php

/*
 * Used by Prospects
 * An event table for prospect tasks
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('member_id')->references('id')->on('members');
            $table->string('card_number');
            $table->boolean('permitted')->default(false);
            $table->string('reason');

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
        Schema::dropIfExists('check_ins');
    }
}
