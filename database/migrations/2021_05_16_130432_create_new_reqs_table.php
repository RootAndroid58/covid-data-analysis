<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewReqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_reqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model');
            $table->longText('data');
            $table->string('message')->nullable();
            $table->integer('status')->nullable();
            $table->longText('admin_message')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_reqs');
    }
}
