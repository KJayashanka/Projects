<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id');
            $table->string('region_code', 5)->unique();
            $table->string('region_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
