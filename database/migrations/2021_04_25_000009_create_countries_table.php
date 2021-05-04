<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('capital')->nullable();
            $table->string('code')->unique();
            $table->string('phone_code');
            $table->string('region');
            $table->string('subregion');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
