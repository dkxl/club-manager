<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('event_id')->references('id')->on('events');
            $table->foreignUlid('member_id')->references('id')->on('members');
            $table->foreignUlid('created_by')->references('id')->on('users');
            $table->text('state');
            $table->text('comments')->nullable();
            $table->boolean('processed')->default(false);  // processed by the overnight batch tasks

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
