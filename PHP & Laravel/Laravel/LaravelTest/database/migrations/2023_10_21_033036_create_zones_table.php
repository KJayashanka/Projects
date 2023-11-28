<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('zone_code')->nullable();
            $table->string('long_description');
            $table->string('short_description');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('zones');
    }
};
