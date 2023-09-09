<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoremedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storemedicines', function (Blueprint $table) {
            $table->id();
            $table->string("name",50);
            $table->string("purchaseType",50);
            $table->string("medicineType",30);
            $table->string("quantity",30);
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storemedicines');
    }
}
