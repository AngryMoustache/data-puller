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
        Schema::create('kanji', function (Blueprint $table) {
            $table->id();
            $table->string('character');
            $table->string('meaning');
            $table->timestamps();
        });

        Schema::create('japanese_translation_kanji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('japanese_translation_id')->constrained('japanese_translations')->cascadeOnDelete();
            $table->foreignId('kanji_id')->constrained('kanji')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('japanese_translation_kanji');
        Schema::dropIfExists('kanji');
    }
};
