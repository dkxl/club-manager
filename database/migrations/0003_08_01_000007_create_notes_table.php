<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('member_id')->references('id')->on('members');
            $table->foreignUlid('created_by')->references('id')->on('users');
            $table->integer('topic')->default(0); // payments, training, etc
            $table->text('note');
            $table->integer('alert')->default(0)->comment('0=no,1=alert,2=closed');

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
        Schema::dropIfExists('notes');
    }
}
