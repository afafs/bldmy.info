<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVcardTestimonials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vcard_testimonials', function (Blueprint $table) {
            $table->id();
            $table->integer('user_vcard_id')->nullable();
            $table->string('image', 50)->nullable();
            $table->string('name', 255)->nullable();
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();
            $table->integer('serial_number')->nullable();
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
        Schema::dropIfExists('user_vcard_testimonials');
    }
}
