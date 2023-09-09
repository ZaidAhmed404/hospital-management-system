<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("medicineId");
            $table->foreign('medicineId')->references('id')->on('storemedicines')->onDelete('cascade')->onUpdate('cascade');
            $table->string("quantity",50);
            $table->boolean("isIssued");
            
            $table->unsignedBigInteger("patientId");
            $table->foreign('patientId')->references('id')->on('patients')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('prescriptions');
    }
}
