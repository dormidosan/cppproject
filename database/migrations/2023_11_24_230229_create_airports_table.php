<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->id();
            $table->string('icao', 10);
            $table->string('iata', 10);
            $table->string('name');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->integer('elevation');
            $table->double('lat', 10, 7);
            $table->double('lon', 10, 7);
            $table->string('tz');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('airports');
    }
};
