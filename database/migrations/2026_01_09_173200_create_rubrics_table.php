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
        Schema::create('rubrics', function (Blueprint $table) {
            // Primary key (ULID)
            $table->ulid('id')->primary();
            
            // Уникальный ключ для внешних ссылок
            $table->ulid('key')->unique()->comment('Уникальный ключ для внешних ссылок');
            
            // Основные поля
            $table->boolean('is_active')->default(true)->comment('Активность записи');
            $table->string('value')->comment('Название рубрики');
            $table->string('slug')->unique()->comment('URL-friendly идентификатор');
            $table->text('description')->nullable()->comment('Описание рубрики');
            
            // Сортировка
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            
            // Временные метки (created_at, updated_at, deleted_at)
            $table->timestamps();
            $table->softDeletes(); // Мягкое удаление (deleted_at)
            
            // Индексы для оптимизации запросов
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrics');
    }
};
