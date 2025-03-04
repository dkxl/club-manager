<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->text('name');
            $table->text('description')->nullable();
            $table->foreignUlid('venue_id')->references('id')->on('venues');
            $table->foreignUlid('instructor_id')->references('id')->on('instructors');
            $table->jsonb('metadata')->nullable(); // event specific data
            $table->timestamp('starts_at')->index();
            $table->timestamp('ends_at')->index();
            $table->foreignUlid('series_id')->nullable()->references('id')->on('event_series');
            $table->boolean('edited_child')->default(false);

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
        Schema::dropIfExists('events');
    }
}
