<?php

use App\Models\Pull;
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
        Pull::query()->update(['story' => '[]']);

        Schema::table('pulls', function (Blueprint $table) {
            $table->json('story')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pulls', function (Blueprint $table) {
            $table->text('story')->change();
        });
    }
};
