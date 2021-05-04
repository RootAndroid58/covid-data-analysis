<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryStatePivotTable extends Migration
{
    public function up()
    {
        Schema::create('country_state', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id', 'country_id_fk_3831852')->references('id')->on('countries')->onDelete('cascade');
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id', 'state_id_fk_3831852')->references('id')->on('states')->onDelete('cascade');
        });
    }
}
