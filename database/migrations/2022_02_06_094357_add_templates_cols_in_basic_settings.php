<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplatesColsInBasicSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->tinyInteger('templates_section')->default(1);
            $table->string('preview_templates_title', 255)->nullable();
            $table->string('preview_templates_subtitle', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_settings', function (Blueprint $table) {
            $table->dropColumn(['templates_section','preview_templates_title','preview_templates_subtitle']);
        });
    }
}
