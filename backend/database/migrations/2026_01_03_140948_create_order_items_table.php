<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       Schema::create('order_items', function (Blueprint $table) {
    $table->id('order_item_id');

    $table->foreignId('order_id')
          ->constrained('orders')
          ->cascadeOnDelete();

    $table->foreignId('product_id')
          ->constrained('products')
          ->cascadeOnDelete();

    // ✅ FIXED foreign key
    $table->unsignedBigInteger('variation_id')->nullable();
    $table->foreign('variation_id')
          ->references('id')
          ->on('product_variation')
          ->cascadeOnDelete();

    $table->integer('quantity');
    $table->decimal('total_price', 10, 2);
    $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
