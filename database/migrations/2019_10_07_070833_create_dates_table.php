<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedSmallInteger('year')->index();
            $table->unsignedTinyInteger('month')->index();
            $table->unsignedTinyInteger('day');
            $table->string('dayofweek');
            $table->unsignedTinyInteger('countofdayOfWeek');
            $table->string('formatted_date');
            $table->string('formatted_date_short');
            $table->string('formatted_date_supershort');
            $table->string('formatted_date_short_no_year');
            $table->string('formatted_date_for_monthlyonweekday');
            $table->string('formatted_date_monthandday');
            $table->string('formatted_date_dayofmonth');
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
        Schema::dropIfExists('dates');
    }
}
