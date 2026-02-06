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
        Schema::create('images', function (Blueprint $table) {
            // Primary key (ULID)
            $table->ulid('id')->primary();
            
            // Уникальный ключ для внешних ссылок
            $table->ulid('key')->unique()->comment('Уникальный ключ для внешних ссылок');
            
            // Основные поля
            $table->boolean('is_active')->default(true)->comment('Активность записи');
            $table->string('hash')->comment('Хэш файла изображения');
            
            // Дополнительные поля для изображения
            $table->string('filename')->comment('Имя файла изображения');
            $table->string('original_name')->nullable()->comment('Оригинальное имя файла');
            $table->string('mime_type')->nullable()->comment('MIME тип файла');
            $table->unsignedBigInteger('size')->nullable()->comment('Размер файла в байтах');
            $table->string('path')->nullable()->comment('Путь к файлу в хранилище');
            
            // Полиморфное отношение (Один ко Многим)
            // Позволяет привязывать изображения к любой сущности (рубрике, категории, проекту и т.д.)
            $table->ulidMorphs('parentable');
            
            // Сортировка
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            
            // Временные метки (created_at, updated_at, deleted_at)
            $table->timestamps();
            $table->softDeletes(); // Мягкое удаление (deleted_at)
            
            // Индексы для оптимизации запросов
            $table->index('is_active');
            $table->index('hash');
            $table->index('sort_order');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
