<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerritoriesTable extends Migration
{
    public function up()
    {
        Schema::create('territories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('region_id');
            $table->string('territory_code', 10)->unique();
            $table->string('territory_name');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('zone_id')->references('id')->on('zones');
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    public function down()
    {
        Schema::dropIfExists('territories');
    }
}
