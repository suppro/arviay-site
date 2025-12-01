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
        // Удаляем старую таблицу categories, если она существует
        Schema::dropIfExists('categories');
        
        // Создаем новую таблицу categories с иерархической структурой
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Родительская категория');
            $table->string('name');
            $table->string('slug')->unique()->comment('URL-адрес категории');
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Связь сама на себя для подкатегорий
            $table->foreign('parent_id', 'fk_categories_parent')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

