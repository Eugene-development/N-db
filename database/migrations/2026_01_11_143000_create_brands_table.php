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
        Schema::create('brands', function (Blueprint $table) {
            // Primary key (ULID)
            $table->ulid('id')->primary();
            
            // Уникальный ключ для внешних ссылок
            $table->ulid('key')->unique()->comment('Уникальный ключ для внешних ссылок');
            
            // Связь с рубрикой (для "Бытовая техника")
            $table->ulid('rubric_id')->nullable()->comment('ID рубрики');
            $table->foreign('rubric_id')->references('id')->on('rubrics')->nullOnDelete();
            
            // Основные поля
            $table->boolean('is_active')->default(true)->comment('Активность записи');
            $table->string('value')->comment('Название бренда');
            $table->string('slug')->unique()->comment('URL-friendly идентификатор');
            $table->text('description')->nullable()->comment('Описание бренда');
            
            // Специфичные поля для бренда
            $table->string('logo')->nullable()->comment('URL логотипа');
            $table->string('country')->nullable()->comment('Страна производителя');
            $table->string('website')->nullable()->comment('Официальный сайт');
            
            // Сортировка
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            
            // Временные метки (created_at, updated_at, deleted_at)
            $table->timestamps();
            $table->softDeletes(); // Мягкое удаление (deleted_at)
            
            // Индексы для оптимизации запросов
            $table->index('rubric_id');
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('slug');
            $table->index('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
