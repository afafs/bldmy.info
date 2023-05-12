<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vcards', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('slug_id')->nullable();
            $table->tinyInteger('direction')->default(1)->comment('1 - ltr, 2 - rtl');
            $table->string('profile_image', 50)->nullable();
            $table->string('cover_image', 50)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('occupation', 255)->nullable();
            $table->text('introduction')->nullable();
            $table->text('information')->nullable();
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
        Schema::dropIfExists('user_vcards');
    }
}
