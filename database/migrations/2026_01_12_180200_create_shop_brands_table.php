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
        Schema::create('shop_brands', function (Blueprint $table) {
            // Primary key (ULID)
            $table->ulid('id')->primary();
            
            // Связь с магазином
            $table->ulid('shop_id')->comment('ID магазина');
            $table->foreign('shop_id')->references('id')->on('shops')->cascadeOnDelete();
            
            // Основные поля
            $table->boolean('is_active')->default(true)->comment('Активность записи');
            $table->string('value')->comment('Название бренда');
            $table->string('logo')->nullable()->comment('URL логотипа бренда');
            
            // Сортировка
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            
            // Временные метки
            $table->timestamps();
            
            // Индексы
            $table->index('shop_id');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_brands');
    }
};
