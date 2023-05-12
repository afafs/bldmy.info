<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPluginToUserBasicSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_basic_settings', function (Blueprint $table) {
            $table->tinyInteger('whatsapp_status')->default(0);
            $table->string('whatsapp_number', 30)->nullable();
            $table->string('whatsapp_header_title', 255)->nullable();
            $table->tinyInteger('whatsapp_popup_status')->default(0);
            $table->text('whatsapp_popup_message')->nullable();
           
            $table->tinyInteger('disqus_status')->default(0);
            $table->string('disqus_short_name', 30)->nullable();
           
            $table->tinyInteger('analytics_status')->default(0);
         
            $table->string('measurement_id', 100)->nullable();

            $table->tinyInteger('pixel_status')->default(0);
            $table->string('pixel_id', 30)->nullable();

            $table->tinyInteger('tawkto_status')->default(0);
            $table->string('tawkto_direct_chat_link', 255)->nullable();

            $table->string('website_title', 100)->nullable();
            $table->string('base_currency_symbol')->nullable();
            $table->string('base_currency_symbol_position')->nullable();
            $table->string('base_currency_text')->nullable();
            $table->decimal('base_currency_rate')->nullable();
            $table->string('base_currency_text_position')->nullable();
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
            //
        });
    }
}
