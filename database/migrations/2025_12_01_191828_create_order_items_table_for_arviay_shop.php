<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id')->nullable();
            
            // Важно сохранять название и цену на момент покупки
            $table->string('product_name');
            $table->string('product_sku', 100);
            $table->decimal('price_at_purchase', 10, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();
            
            $table->foreign('order_id', 'fk_order_items_order')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
                
            $table->foreign('product_id', 'fk_order_items_product')
                ->references('id')
                ->on('products')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
