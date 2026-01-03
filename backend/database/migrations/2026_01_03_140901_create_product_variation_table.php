<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variation', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('size');
            $table->string('color');
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variation');
    }
};
