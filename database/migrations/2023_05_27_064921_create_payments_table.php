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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('travel_options_id');
            $table->string('fullname', 255);
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('tx_ref', 70);
            $table->decimal('amount_paid', 8,2);
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->foreign('booking_id')->references('id')->on('bookings');
            $table->foreign('travel_options_id')->references('id')->on('travel_options');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
