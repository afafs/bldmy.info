<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_inputs', function (Blueprint $table) {
            $table->id();
            $table->integer('language_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->tinyInteger('type')->nullable()->comment('1-text, 2-select, 3-checkbox, 4-textarea, 5-file');
            $table->string('label')->nullable();
            $table->string('name')->nullable();
            $table->string('placeholder')->nullable();
            $table->tinyInteger('required')->nullable()->default(0)->comment('1 - required, 0 - optional');
            $table->integer('searchable')->nullable()->default(0);
            $table->integer('order_number')->nullable()->default(0);
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
        Schema::dropIfExists('form_inputs');
    }
}
