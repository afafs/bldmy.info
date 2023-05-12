<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDomainRequestSuccessMessageColToBasicExtendeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_extendeds', function (Blueprint $table) {
            $table->string('domain_request_success_message', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('basic_extendeds', function (Blueprint $table) {
            $table->dropColumn('domain_request_success_message');
        });
    }
}
