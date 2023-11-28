<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('zone');
            $table->string('region');
            $table->string('territory');
            $table->string('distributor');
            $table->text('remark');
            $table->date('order_date');
            $table->string('po_number')->unique();
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
}

