<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Таблица проектов мебели (mebel_projects) для карточек проекта.
     * Структура: Rubric -> Category -> MebelProject
     * 
     * Примечание: Таблица уже была частично создана ранее,
     * эта миграция добавляет недостающие поля.
     */
    public function up(): void
    {
        // Если таблица не существует - создаём полностью
        if (!Schema::hasTable('mebel_projects')) {
            Schema::create('mebel_projects', function (Blueprint $table) {
                // Primary key (ULID)
                $table->ulid('id')->primary();
                
                // Уникальный ключ для внешних ссылок
                $table->ulid('key')->unique()->comment('Уникальный ключ для внешних ссылок');
                
                // Связь с категорией
                $table->ulid('category_id')->comment('ID категории');
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
                
                // Основные поля проекта
                $table->boolean('is_active')->default(true)->comment('Активность записи');
                $table->string('value')->comment('Название проекта');
                $table->string('slug')->unique()->comment('URL-friendly идентификатор');
                $table->text('description')->nullable()->comment('Описание проекта');
                $table->text('short_description')->nullable()->comment('Краткое описание');
                
                // Цена
                $table->decimal('price', 12, 2)->nullable()->comment('Цена проекта');
                $table->decimal('old_price', 12, 2)->nullable()->comment('Старая цена (для скидок)');
                
                // Дополнительные данные
                $table->json('meta')->nullable()->comment('Мета-данные (SEO, etc.)');
                
                // Сортировка
                $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
                
                // Статусы
                $table->boolean('is_featured')->default(false)->comment('Избранный проект');
                $table->boolean('is_new')->default(false)->comment('Новинка');
                
                // Поля аудита - кто создал/обновил/удалил
                $table->ulid('created_by')->nullable()->comment('ID пользователя, создавшего запись');
                $table->ulid('updated_by')->nullable()->comment('ID пользователя, обновившего запись');
                $table->ulid('deleted_by')->nullable()->comment('ID пользователя, удалившего запись');
                
                // Временные метки (created_at, updated_at, deleted_at)
                $table->timestamps();
                $table->softDeletes(); // Мягкое удаление (deleted_at)
                
                // Индексы для оптимизации запросов
                $table->index('category_id');
                $table->index('is_active');
                $table->index('sort_order');
                $table->index('slug');
                $table->index('is_featured');
                $table->index('is_new');
                $table->index(['category_id', 'is_active']);
            });
        } else {
            // Таблица существует - добавляем недостающие поля
            Schema::table('mebel_projects', function (Blueprint $table) {
                // Добавляем key если не существует
                if (!Schema::hasColumn('mebel_projects', 'key')) {
                    $table->ulid('key')->after('id')->unique()->comment('Уникальный ключ для внешних ссылок');
                }
                
                // Добавляем short_description если не существует
                if (!Schema::hasColumn('mebel_projects', 'short_description')) {
                    $table->text('short_description')->nullable()->after('description')->comment('Краткое описание');
                }
                
                // Добавляем is_new если не существует
                if (!Schema::hasColumn('mebel_projects', 'is_new')) {
                    $table->boolean('is_new')->default(false)->after('is_featured')->comment('Новинка');
                }
                
                // Добавляем meta если не существует
                if (!Schema::hasColumn('mebel_projects', 'meta')) {
                    $table->json('meta')->nullable()->after('old_price')->comment('Мета-данные (SEO, etc.)');
                }
                
                // Добавляем поля аудита если не существуют
                if (!Schema::hasColumn('mebel_projects', 'created_by')) {
                    $table->ulid('created_by')->nullable()->after('sort_order')->comment('ID пользователя, создавшего запись');
                }
                if (!Schema::hasColumn('mebel_projects', 'updated_by')) {
                    $table->ulid('updated_by')->nullable()->after('created_by')->comment('ID пользователя, обновившего запись');
                }
                if (!Schema::hasColumn('mebel_projects', 'deleted_by')) {
                    $table->ulid('deleted_by')->nullable()->after('updated_by')->comment('ID пользователя, удалившего запись');
                }
            });
            
            // Добавляем индекс для is_new
            Schema::table('mebel_projects', function (Blueprint $table) {
                if (!Schema::hasColumn('mebel_projects', 'is_new')) {
                    return;
                }
                // Проверяем наличие индекса через try-catch
                try {
                    $table->index('is_new');
                } catch (\Exception $e) {
                    // Индекс уже существует
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mebel_projects');
    }
};
