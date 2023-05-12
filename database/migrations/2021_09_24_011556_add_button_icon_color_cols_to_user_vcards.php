<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddButtonIconColorColsToUserVcards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_vcards', function (Blueprint $table) {
            $table->string('call_button_color', 20)->default("ed2476");
            $table->string('whatsapp_button_color', 20)->default("25d366");
            $table->string('mail_button_color', 20)->default("BB001B");
            $table->string('add_to_contact_button_color', 20)->default("FF5C58");
            $table->string('share_vcard_button_color', 20)->default("FF5C58");

            $table->string('phone_icon_color', 20)->default("171515");
            $table->string('email_icon_color', 20)->default("171515");
            $table->string('address_icon_color', 20)->default("171515");
            $table->string('website_url_icon_color', 20)->default("171515");
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
            $table->dropColumn(['call_button_color','whatsapp_button_color','mail_button_color','add_to_contact_button_color','share_vcard_button_color','phone_icon_color','email_icon_color','address_icon_color','website_url_icon_color']);
        });
    }
}
