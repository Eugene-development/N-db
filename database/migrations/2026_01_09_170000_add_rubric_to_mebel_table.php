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
        Schema::table('mebel', function (Blueprint $table) {
            $table->string('rubric')->default('mebel')->after('slug')->comment('Рубрика (mebel, appliances, countertops, plumbing, fittings, accessories)');
            $table->index('rubric');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mebel', function (Blueprint $table) {
            $table->dropIndex(['rubric']);
            $table->dropColumn('rubric');
        });
    }
};
