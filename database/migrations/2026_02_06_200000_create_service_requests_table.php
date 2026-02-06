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
        Schema::create('service_requests', function (Blueprint $table) {
            // Primary key (ULID)
            $table->ulid('id')->primary();
            
            // Тип услуги
            $table->string('service_type', 50)->comment('Тип услуги: consultation, design-project, furniture-project, assembly, measurement');
            
            // Данные клиента
            $table->string('name')->comment('Имя клиента');
            $table->string('phone', 50)->comment('Телефон клиента');
            $table->text('message')->nullable()->comment('Сообщение от клиента');
            
            // Статус заявки
            $table->string('status', 20)->default('new')->comment('Статус: new, processed, completed, cancelled');
            
            // Технические данные
            $table->string('ip_address', 45)->nullable()->comment('IP-адрес клиента');
            $table->text('user_agent')->nullable()->comment('User-Agent браузера');
            $table->string('source_url', 500)->nullable()->comment('URL страницы, с которой отправлена заявка');
            
            // Временные метки
            $table->timestamps();
            
            // Индексы для оптимизации запросов
            $table->index('service_type');
            $table->index('status');
            $table->index('phone');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
