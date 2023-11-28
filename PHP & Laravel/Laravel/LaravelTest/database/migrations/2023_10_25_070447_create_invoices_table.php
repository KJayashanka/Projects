<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->unique();
            $table->string('invoice_number');
            $table->string('po_number');
            $table->unsignedBigInteger('distributor_id');
            $table->date('invoice_date');
            $table->timestamps();

            $table->foreign('distributor_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
