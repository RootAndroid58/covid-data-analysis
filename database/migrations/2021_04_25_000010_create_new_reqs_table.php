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
            $table->integer('status')->nullable();
            $table->string('catogary')->nullable();
            $table->string('phone_no');
            $table->string('email')->nullable();
            $table->longText('address')->nullable();
            $table->string('details')->nullable();
            $table->longText('note')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
