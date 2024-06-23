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
        Schema::table('rating_categories', function (Blueprint $table) {
            $table->after('slug', function (Blueprint $table) {
                $table->string('zero_value')->nullable();
                $table->string('ten_value')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rating_categories', function (Blueprint $table) {
            $table->dropColumn('zero_value');
            $table->dropColumn('ten_value');
        });
    }
};
