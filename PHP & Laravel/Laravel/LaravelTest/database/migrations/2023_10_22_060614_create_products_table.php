<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code');
            $table->string('sku_name');
            $table->decimal('distributor_price', 10, 2);
            $table->decimal('mrp', 10, 2);
            $table->integer('units');
            $table->enum('measure', ['ml', 'g', 'kg']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};

