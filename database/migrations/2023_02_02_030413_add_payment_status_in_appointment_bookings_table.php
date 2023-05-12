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
        Schema::table('appointment_bookings', function (Blueprint $table) {
            $table->integer('payment_status')->default(1)->comment('1=pending, 2=paid, 3=advanced, 4=rejected');
            $table->integer('status')->default(1)->comment('1=pending, 2=approved, 3=completed, 4=rejected')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointment_bookings', function (Blueprint $table) {
            //
        });
    }
};
