<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('travel_option_id');
            $table->string('from', 255);
            $table->string('to', 255);
            $table->string('phone', 20)->nullable();
            $table->string('booking_email')->nullable();
            $table->timestamp('departure_date');
            $table->timestamp('arrival_date')->nullable();
            $table->unsignedBigInteger('num_guest')->default(1);
            $table->decimal('amount', 8,2)->default(0);
            $table->boolean('payment_status')->default(0);
            $table->boolean('booking_status')->default(1)->comment('1= ACTIVE, 0 = CANCELLED booking');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('travel_option_id')->references('id')->on('travel_options');
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
};
