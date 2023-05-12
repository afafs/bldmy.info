<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExternalLinkToUserVcardServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_vcard_services', function (Blueprint $table) {
            $table->tinyInteger('external_link_status')->default(0)->comment('1 - active, 0 - deactive');
            $table->text('external_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_vcard_services', function (Blueprint $table) {
            $table->dropColumn(['external_link_status','external_link']);
        });
    }
}
