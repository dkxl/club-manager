<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->text('name');
            $table->boolean('free_classes')->default(false);  // Includes exercise classes
            $table->boolean('available')->default(true);  // Available for new contracts
            $table->decimal('jf_amount', 6, 2);   // Joining Fee
            $table->decimal('puf_amount', 6, 2);  // First month Amount
            $table->decimal('dd_amount', 6, 2);   // DD monthly amount
            $table->integer('term_months')->default(0);
            $table->time('start_time');                        // Earliest visit time
            $table->time('end_time');                          // Latest visit time

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
        Schema::dropIfExists('membership_plans');
    }
}
