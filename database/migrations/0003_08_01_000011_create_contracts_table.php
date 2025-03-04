<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('member_id')->references('id')->on('members');
            $table->foreignUlid('plan_id')->references('id')->on('membership_plans');
            $table->integer('state')->default(0);   // contract state

            // The defaults for this membership plan can be overridden. So store here too.
            $table->integer('term_months')->default(0);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('jf_amount', 6, 2)->nullable();   // Joining fee
            $table->decimal('puf_amount', 6, 2)->nullable();  // First mount amount
            $table->decimal('dd_amount', 6, 2)->nullable();   // Monthly payment
            $table->integer('dd_day')->nullable();
            $table->date('dd_first')->nullable();
            $table->date('dd_last')->nullable();
            $table->date('canx_date')->nullable();

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
        Schema::drop('contracts');
    }
}
