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
            $table->string('product_code', 10);
            $table->string('product_name');
            $table->decimal('discount', 8, 2)->default(0.00);
            $table->decimal('price', 10, 2);
            $table->date('expiry_date');
            $table->unsignedBigInteger('issue_type_id')->nullable(); // Nullable because it's a foreign key
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('issue_type_id')->references('id')->on('free_issues')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['issue_type_id']);
        });

        Schema::dropIfExists('products');
    }
}
