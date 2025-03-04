<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->ulid('id')->primary(); // primary key

            // personal info
            $table->text('first_name');
            $table->text('last_name');
            $table->text('email')->nullable();
            $table->text('phone')->nullable();
            $table->text('honorific')->nullable();
            $table->text('gender')->nullable();
            $table->date('dob')->nullable();
            $table->text('card_number')->nullable();

            // address
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->text('address_3')->nullable();
            $table->text('address_4')->nullable();
            $table->text('town')->nullable();
            $table->text('county')->nullable();
            $table->text('postcode')->nullable();

            // other contacts
            $table->text('emerg_contact')->nullable();
            $table->text('emerg_phone')->nullable();

            // additional info
            $table->date('med_dec_date')->nullable();


            // website integration
            $table->integer('wordpress_id')->nullable();

            // Housekeeping timestamps
            $table->timestamps();
            $table->softDeletes();

            // indexes
            $table->index('card_number');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
