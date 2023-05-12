<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_qr_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->text('url')->nullable();
            $table->string('image', 50)->nullable();
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
        Schema::dropIfExists('user_qr_codes');
    }
}
