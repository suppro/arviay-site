<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Удаляем старые таблицы, связанные с продуктами
        Schema::dropIfExists('product_ingredients');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        
        // Создаем новую таблицу products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku', 100)->unique()->comment('Артикул (важно для 1С)');
            $table->text('description')->nullable();
            $table->json('technical_specs')->nullable()->comment('Гибкие тех. характеристики');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('stock_quantity')->default(0)->comment('Остаток на складе');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('category_id', 'fk_products_category')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
        });
        
        // Добавляем полнотекстовый индекс для поиска (только для MySQL 5.6+)
        try {
            DB::statement('ALTER TABLE products ADD FULLTEXT INDEX idx_fulltext_search (name, sku, description)');
        } catch (\Exception $e) {
            // Если полнотекстовый индекс не поддерживается, создаем обычные индексы
            Schema::table('products', function (Blueprint $table) {
                $table->index('name');
                $table->index('sku');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

