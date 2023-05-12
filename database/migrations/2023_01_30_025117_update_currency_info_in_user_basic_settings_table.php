<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            $table->string('base_currency_symbol')->default('$')->change();
            $table->string('base_currency_symbol_position')->default('left')->change();
            $table->string('base_currency_text')->default('USD')->change();
            $table->string('base_currency_rate')->default(1)->change();
            $table->string('base_currency_text_position')->default('left')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            $table->string('base_currency_symbol')->change();
            $table->string('base_currency_symbol_position')->change();
            $table->string('base_currency_text')->change();
            $table->string('base_currency_rate')->change();
            $table->string('base_currency_text_position')->change();
        });
    }
};
