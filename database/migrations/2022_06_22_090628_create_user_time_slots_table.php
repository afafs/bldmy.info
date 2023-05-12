<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_time_slots', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('day')->nullable();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->integer('max_booking')->nullable();
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
        Schema::dropIfExists('user_time_slots');
    }
}
