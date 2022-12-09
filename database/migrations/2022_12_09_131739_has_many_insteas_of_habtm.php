<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->foreignId('tag_group_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();
        });

        Schema::dropIfExists('tag_tag_group');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('tag_tag_group', function (Blueprint $table) {
            $table->foreignId('tag_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('tag_group_id')
                ->constrained()
                ->cascadeOnDelete();
        });

        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['tag_group_id']);
            $table->dropColumn('tag_group_id');
        });
    }
};
