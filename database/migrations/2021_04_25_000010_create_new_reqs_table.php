<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewReqsTable extends Migration
{
    public function up()
    {
        Schema::create('new_reqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->longText('extra')->nullable();
            $table->integer('status')->nullable();
            $table->string('catogary')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
