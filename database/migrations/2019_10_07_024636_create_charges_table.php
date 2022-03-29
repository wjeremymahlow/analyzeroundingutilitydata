<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('dateofservice');
            $table->string('patientname')->nullable();
            $table->string('room')->nullable();
            $table->string('roundingmdabbreviations')->nullable();
            $table->string('cpsmrn')->nullable();
            $table->string('powerchartmrn');
            $table->string('icd10code1')->nullable();
            $table->string('icd10code2')->nullable();
            $table->string('icd10code3')->nullable();
            $table->string('icd10code4')->nullable();
            $table->string('referringmd')->nullable();
            $table->string('cptcode');
            $table->string('billingmdabbreviation');
            $table->string('chargestatus');
            $table->boolean('error')->default(false);
            $table->string('errormessage')->nullable();
            $table->unsignedInteger('attending_id')->nullable();
            $table->unsignedInteger('patient_id')->nullable();
            $table->unsignedInteger('date_id')->nullable();
            $table->timestamps();
        });

        Schema::create('charge_icd10code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('charge_id');
            $table->integer('icd10code_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charges');
        Schema::dropIfExists('charge_icd10code');
    }
}
