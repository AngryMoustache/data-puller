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
        Schema::table('pulls', function (Blueprint $table) {
            $table->json('thumbnails')->after('source_url')->nullable();
        });

        Schema::table('tag_tag_group', function (Blueprint $table) {
            $table->dropColumn('thumbnail_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pulls', function (Blueprint $table) {
            $table->dropColumn('thumbnails');
        });

        Schema::table('tag_tag_group', function (Blueprint $table) {
            $table->string('thumbnail_url')->nullable();
        });
    }
};
