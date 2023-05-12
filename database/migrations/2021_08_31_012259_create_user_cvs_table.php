<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cvs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('cv_name')->nullable();
            $table->tinyInteger('direction')->default(1)->comment('1 - LTR, 2 - RTL');
            $table->string('image', 50)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('occupation', 255)->nullable();
            $table->string('base_color', 20)->nullable();
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
        Schema::dropIfExists('user_cvs');
    }
}
