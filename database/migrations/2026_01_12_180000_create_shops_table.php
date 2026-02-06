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
        Schema::create('shops', function (Blueprint $table) {
            // Primary key (ULID)
            $table->ulid('id')->primary();
            
            // Уникальный ключ для внешних ссылок
            $table->ulid('key')->unique()->comment('Уникальный ключ для внешних ссылок');
            
            // Связь с рубрикой (для "Фурнитура" или "Аксессуары")
            $table->ulid('rubric_id')->nullable()->comment('ID рубрики');
            $table->foreign('rubric_id')->references('id')->on('rubrics')->nullOnDelete();
            
            // Основные поля
            $table->boolean('is_active')->default(true)->comment('Активность записи');
            $table->string('value')->comment('Название магазина');
            $table->string('slug')->unique()->comment('URL-friendly идентификатор');
            $table->text('description')->nullable()->comment('Описание магазина');
            
            // Контактная информация
            $table->string('logo')->nullable()->comment('URL логотипа');
            $table->string('website')->nullable()->comment('Сайт магазина');
            $table->string('phone')->nullable()->comment('Телефон');
            $table->string('email')->nullable()->comment('Email');
            
            // Сортировка
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            
            // Временные метки
            $table->timestamps();
            $table->softDeletes();
            
            // Индексы
            $table->index('rubric_id');
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
        Schema::dropIfExists('shops');
    }
};
