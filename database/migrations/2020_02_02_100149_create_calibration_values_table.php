<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalibrationValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calibration_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('serial_no');
            $table->unsignedBigInteger('calibration_id');
            $table->integer('dip');
            $table->double('litres');
            $table->timestamps();

            $table->foreign('calibration_id')->references('id')->on('calibrations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calibration_values');
    }
}
