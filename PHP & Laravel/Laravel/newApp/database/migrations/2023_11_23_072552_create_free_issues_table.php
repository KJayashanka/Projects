<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFreeIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('free_issues', function (Blueprint $table) {
            $table->id();
            $table->string('free_issue_label');
            $table->string('issue_type');
            $table->string('purchase_product');
            $table->string('free_product');
            $table->integer('purchase_quantity');
            $table->integer('free_quantity');
            $table->integer('lower_limit');
            $table->integer('upper_limit');
            $table->unsignedBigInteger('purchase_product_id')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('purchase_product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('free_issues');
    }
}

