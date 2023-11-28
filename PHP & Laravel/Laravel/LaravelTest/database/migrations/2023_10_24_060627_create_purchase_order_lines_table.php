<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrderLinesTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_order_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->string('sku_code');
            $table->string('sku_name');
            $table->decimal('distributor_price', 10, 2);
            $table->decimal('mrp', 10, 2);
            $table->integer('units');
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('purchase_order_lines');
    }
}
