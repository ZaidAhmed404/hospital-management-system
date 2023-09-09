<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchermedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchermedicines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("medicineId");
            $table->foreign('medicineId')->references('id')->on('storemedicines')->onDelete('cascade')->onUpdate('cascade');
            $table->string("quantity",50);
            
            $table->unsignedBigInteger("voucherId");
            $table->foreign('voucherId')->references('id')->on('vouchers')->onDelete('cascade')->onUpdate('cascade');
            
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
        Schema::dropIfExists('vouchermedicines');
    }
}
