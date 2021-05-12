<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceSubCategoryPivotTable extends Migration
{
    public function up()
    {
        Schema::create('resource_sub_category', function (Blueprint $table) {
            $table->unsignedBigInteger('resource_id');
            $table->foreign('resource_id', 'resource_id_fk_3890450')->references('id')->on('resources')->onDelete('cascade');
            $table->unsignedBigInteger('sub_category_id');
            $table->foreign('sub_category_id', 'sub_category_id_fk_3890450')->references('id')->on('sub_categories')->onDelete('cascade');
        });
    }
}
