<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('phone_no');
            $table->string('email')->nullable();
            $table->longText('address')->nullable();
            $table->longText('details')->nullable();
            $table->longText('note')->nullable();
            $table->string('up_vote')->nullable();
            $table->string('down_vote')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
