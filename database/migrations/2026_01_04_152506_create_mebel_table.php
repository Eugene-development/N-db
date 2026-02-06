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
        Schema::create('mebel', function (Blueprint $table) {
            // Primary key (ULID)
            $table->ulid('id')->primary();
            
            // Основные поля
            $table->boolean('is_active')->default(true)->comment('Активность записи');
            $table->string('value')->comment('Значение');
            $table->text('description')->nullable()->comment('Описание');
            $table->string('bg')->nullable()->comment('Фон (цвет/изображение)');
            
            // Дополнительные рекомендованные поля
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            
            // Поля аудита - кто создал/обновил/удалил
            $table->ulid('created_by')->nullable()->comment('ID пользователя, создавшего запись');
            $table->ulid('updated_by')->nullable()->comment('ID пользователя, обновившего запись');
            $table->ulid('deleted_by')->nullable()->comment('ID пользователя, удалившего запись');
            
            // Временные метки (created_at, updated_at, deleted_at)
            $table->timestamps();
            $table->softDeletes(); // Мягкое удаление (deleted_at)
            
            // Индексы для оптимизации запросов
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mebel');
    }
};
