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
        Schema::create('tag_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pull_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->boolean('is_main')->default(true);
            $table->timestamps();
        });

        Schema::create('tag_tag_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_group_id')->constrained()->cascadeOnDelete();
            $table->string('thumbnail_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_tag_group');
        Schema::dropIfExists('tag_groups');
    }
};
