<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCityStatePivotTable extends Migration
{
    public function up()
    {
        Schema::create('city_state', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id', 'state_id_fk_3831853')->references('id')->on('states')->onDelete('cascade');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id', 'city_id_fk_3831853')->references('id')->on('cities')->onDelete('cascade');
        });
    }
}
