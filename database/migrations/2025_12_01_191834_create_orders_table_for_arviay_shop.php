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
        // Удаляем старую таблицу orders, если она существует
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        
        // Создаем новую таблицу orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->comment('NULL для гостевых заказов');
            $table->enum('status', ['new', 'processing', 'completed', 'cancelled'])->default('new');
            $table->decimal('total_amount', 10, 2);
            $table->string('payment_method', 50);
            $table->string('delivery_method', 50);
            $table->json('customer_details')->comment('Имя, телефон, адрес доставки');
            $table->boolean('is_synced_to_1c')->default(false)->comment('Флаг выгрузки в 1С');
            $table->timestamps();
            
            $table->foreign('user_id', 'fk_orders_user')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

