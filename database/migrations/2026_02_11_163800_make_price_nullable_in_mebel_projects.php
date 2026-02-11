<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Делаем поля price и old_price nullable в таблице mebel_projects.
     * Цена и старая цена теперь необязательны при создании товара.
     */
    public function up(): void
    {
        Schema::table('mebel_projects', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable()->comment('Цена товара')->change();
            $table->decimal('old_price', 12, 2)->nullable()->comment('Старая цена (для скидок)')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mebel_projects', function (Blueprint $table) {
            $table->decimal('price', 12, 2)->nullable(false)->default(0)->comment('Цена товара')->change();
            $table->decimal('old_price', 12, 2)->nullable(false)->default(0)->comment('Старая цена (для скидок)')->change();
        });
    }
};
