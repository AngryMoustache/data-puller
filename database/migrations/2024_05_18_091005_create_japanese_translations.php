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
        Schema::create('japanese_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pull_id')->nullable()->constrained('pulls')->nullOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('attachments')->nullOnDelete();
            $table->text('original')->nullable();
            $table->text('translation')->nullable();
            $table->json('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('japanese_translations');
    }
};
