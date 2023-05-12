<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreferencesToUserVcards extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_vcards', function (Blueprint $table) {
            $table->string('preferences', 255)->default('["Call","Whatsapp","Mail","Add to Contact","Share vCard","Information","About Us","Video","Services","Projects","Testimonials","Enquiry Form"]');
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
            $table->dropColumn('preferences');
        });
    }
}
