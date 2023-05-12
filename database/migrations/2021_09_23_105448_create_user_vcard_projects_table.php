<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVcardProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vcard_projects', function (Blueprint $table) {
            $table->id();
            $table->integer('user_vcard_id')->nullable();
            $table->string('image', 50)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('short_details')->nullable();
            $table->tinyInteger('external_link_status')->default(0)->comment('1 - active, 0 - deactive');
            $table->text('external_link')->nullable();
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
        Schema::dropIfExists('user_vcard_projects');
    }
}
