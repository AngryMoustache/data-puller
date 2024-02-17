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
        Schema::create('saved_tag_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('attachment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('saved_tag_group_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saved_tag_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_tag_group_tag');
        Schema::dropIfExists('saved_tag_groups');
    }
};
