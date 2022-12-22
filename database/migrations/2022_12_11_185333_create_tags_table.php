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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('tags')->setNullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('long_name');
            $table->string('color');
            $table->boolean('hidden')->default(0);
            $table->timestamps();
        });

        Schema::create('pull_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pull_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_pull');
        Schema::dropIfExists('tags');
    }
};
