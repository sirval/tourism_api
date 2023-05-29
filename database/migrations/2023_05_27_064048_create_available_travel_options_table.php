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
        Schema::create('available_travel_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_option_id');
            $table->timestamp('date')->nullable();
            $table->string('location', 255)->nullable();
            $table->decimal('min_price_range', 10,2)->default(0.00);
            $table->decimal('max_price_range', 10,2)->default(0.00);
            $table->string('type', 155)->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('available_travel_options');
    }
};
