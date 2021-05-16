<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToNewReqsTable extends Migration
{
    public function up()
    {
        Schema::table('new_reqs', function (Blueprint $table) {
            $table->unsignedBigInteger('email_id');
            $table->foreign('email_id', 'email_fk_3915753')->references('id')->on('users');
        });
    }
}
