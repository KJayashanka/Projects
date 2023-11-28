<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('discount_label');
            $table->string('purchase_product');
            $table->decimal('discount_amount', 8, 2);
            $table->unsignedBigInteger('p_id')->nullable();
            $table->timestamps();

            $table->foreign('p_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
