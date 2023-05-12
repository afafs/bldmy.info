<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDefaultColorForIconColorsOfVcard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_vcards', function (Blueprint $table) {
            $table->string('phone_icon_color', 20)->default("FFB830")->change();
            $table->string('email_icon_color', 20)->default("FFB830")->change();
            $table->string('address_icon_color', 20)->default("FFB830")->change();
            $table->string('website_url_icon_color', 20)->default("FFB830")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_vcards', function (Blueprint $table) {
            $table->string('phone_icon_color', 20)->default("171515")->change();
            $table->string('email_icon_color', 20)->default("171515")->change();
            $table->string('address_icon_color', 20)->default("171515")->change();
            $table->string('website_url_icon_color', 20)->default("171515")->change();
        });
    }
}
