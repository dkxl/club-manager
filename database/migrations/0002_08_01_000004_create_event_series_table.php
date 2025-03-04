<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_series', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->text('name');
            $table->text('description')->nullable();
            $table->foreignUlid('venue_id')->references('id')->on('venues');
            $table->foreignUlid('instructor_id')->references('id')->on('instructors');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->string('repeat_type');
            $table->date('repeat_until');
            $table->jsonb('metadata')->nullable(); // event specific data to associate with the series

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
        Schema::dropIfExists('event_series');
    }
}
