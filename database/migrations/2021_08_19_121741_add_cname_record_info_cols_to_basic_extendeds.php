<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCnameRecordInfoColsToBasicExtendeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basic_extendeds', function (Blueprint $table) {
            $table->string('cname_record_section_title', 255)->nullable();
            $table->text('cname_record_section_text')->nullable();
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
            $table->dropColumn(['cname_record_section_title', 'cname_record_section_text']);
        });
    }
}
