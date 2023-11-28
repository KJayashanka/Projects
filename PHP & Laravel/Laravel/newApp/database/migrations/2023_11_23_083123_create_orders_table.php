<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('order_number');
            $table->string('product_name');
            $table->string('product_code');
            $table->decimal('price', 10, 2);
            $table->integer('purchase_quantity');
            $table->integer('free_quantity')->default(0);
            $table->decimal('amount', 10, 2);
            $table->dateTime('order_date');
            $table->dateTime('order_time');
            $table->decimal('discount', 5, 2);
            $table->decimal('net_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
