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
            $table->id('id_repair');
            $table->bigInteger('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id_car_owner')->on('car_owners');
            $table->bigInteger('service_id')->unsigned();
            $table->foreign('service_id')->references('id_service')->on('services');
            $table->bigInteger('mechanic_id')->unsigned();
            $table->foreign('mechanic_id')->references('id_mechanic')->on('mechanics');
            $table->dateTime('car_entry_date');
            $table->string('notes')->nullable();
            $table->bigInteger('repair_status')->unsigned();
            $table->foreign('repair_status')->references('id_status')->on('statuses');
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
