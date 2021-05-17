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
            $table->foreign('email_id', 'email_fk_3925365')->references('id')->on('users');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id', 'created_by_fk_3927032')->references('id')->on('users');
        });
    }
}
