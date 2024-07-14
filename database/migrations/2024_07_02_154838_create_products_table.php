<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('product_type');
            $table->string('product_sku');
            $table->text('product_description');
            $table->integer('price');
            $table->unsignedBigInteger('created_by'); // Add this line
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade'); // Add this line for foreign key constraint
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
