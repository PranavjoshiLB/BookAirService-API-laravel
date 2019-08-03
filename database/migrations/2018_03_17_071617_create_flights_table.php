<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function ($collection) {
            /**$table->bigIncrements('id');
             * $table->string('currency');
             * $table->date('date_of_Departure');
             * $table->string('time_of_departure');
             * $table->date('date_of_arrival');
             * $table->string('time_of_arrival');
             * $table->string('departure_location');
             * $table->string('departure_terminal');
             * $table->string('arrival_location');
             * $table->string('arrival_terminal');
             * $table->string('company');
             * $table->integer('journey_id')->unsigned();
             * $table->timestamps();
             * $table->foreign('journey_id')->references('id')->on('journeys');
             ***/

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}
