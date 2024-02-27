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
        Schema::table('reservations',function (Blueprint $table){
            $table->string('pickup_region', 200)->nullable();
            $table->enum('pay_method', ['cash', 'card'])->nullable();
            $table->dateTime('pickup_time')->nullable();
            $table->string('cancellation_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
