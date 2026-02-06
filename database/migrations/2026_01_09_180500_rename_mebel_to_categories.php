<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Note: Таблица уже была переименована из mebel в categories
     */
    public function up(): void
    {
        // Удаляем старое поле rubric если существует
        if (Schema::hasColumn('categories', 'rubric')) {
            // Удаляем индекс напрямую через SQL (если существует)
            try {
                DB::statement('DROP INDEX `mebel_rubric_index` ON `categories`');
            } catch (\Exception $e) {
                // Индекс мог называться по-другому или не существовать
            }
            
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('rubric');
            });
        }

        // Добавляем связь с таблицей rubrics
        if (!Schema::hasColumn('categories', 'rubric_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->ulid('rubric_id')->after('slug')->nullable()->comment('ID рубрики');
                $table->foreign('rubric_id')->references('id')->on('rubrics')->onDelete('cascade');
                $table->index('rubric_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'rubric_id')) {
                $table->dropForeign(['rubric_id']);
                $table->dropIndex(['rubric_id']);
                $table->dropColumn('rubric_id');
            }
        });

        if (!Schema::hasColumn('categories', 'rubric')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('rubric')->default('mebel')->after('slug')->comment('Рубрика');
                $table->index('rubric');
            });
        }

        // Переименовываем обратно
        if (Schema::hasTable('categories') && !Schema::hasTable('mebel')) {
            Schema::rename('categories', 'mebel');
        }
    }
};
