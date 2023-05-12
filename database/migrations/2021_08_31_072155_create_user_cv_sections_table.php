<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCvSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cv_sections', function (Blueprint $table) {
            $table->id();
            $table->integer('user_cv_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('icon', 50)->nullable();
            $table->tinyInteger('column')->comment('1 - left, 2 - right')->default(1);
            $table->tinyInteger('order')->default(1);
            $table->longText('content')->nullable();
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
        Schema::dropIfExists('user_cv_sections');
    }
}
