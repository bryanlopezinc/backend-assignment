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
        Schema::create('vessels_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vessel_id')->index();
            $table->unsignedInteger('status');
            $table->unsignedBigInteger('station_id');
            $table->unsignedInteger('speed');
            $table->decimal('longitude', 11, 8)->index();
            $table->decimal('latitude', 10, 8)->index();
            $table->unsignedInteger('course');
            $table->unsignedInteger('heading');
            $table->integer('rate_of_turn')->nullable()->default(null);
            $table->unsignedInteger('timestamp')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vessels_positions');
    }
};
