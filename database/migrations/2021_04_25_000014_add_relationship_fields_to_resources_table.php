<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToResourcesTable extends Migration
{
    public function up()
    {
        Schema::table('resources', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id', 'city_fk_3768866')->references('id')->on('cities');
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id', 'country_fk_3775707')->references('id')->on('countries')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id', 'state_fk_3775708')->references('id')->on('states');
        });
    }
}
