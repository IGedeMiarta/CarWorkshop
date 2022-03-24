<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarRepairsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_repairs', function (Blueprint $table) {
            $table->id('id_repairs');
            $table->bigInteger('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id_car_owners')->on('car_owners');
            $table->bigInteger('mechanic_id')->unsigned();
            $table->foreign('mechanic_id')->references('id_mechanics')->on('mechanics');
            $table->dateTime('car_entry');
            $table->string('note')->nullable();
            $table->bigInteger('status_id')->unsigned();
            $table->foreign('status_id')->references('id_status')->on('statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('car_repairs');
    }
}
