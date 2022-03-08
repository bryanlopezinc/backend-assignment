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
        Schema::create('vessels_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mmsi')->index();
            $table->boolean('status');
            $table->unsignedBigInteger('station_id');
            $table->unsignedInteger('speed')->unsigned();
            $table->decimal('longitude', 11, 8)->index();
            $table->decimal('latitude', 10, 8)->index();
            $table->unsignedInteger('course');
            $table->unsignedInteger('heading');
            $table->integer('rate_of_turn')->nullable()->default(null);
            $table->unsignedInteger('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vessels_tracks');
    }
};
