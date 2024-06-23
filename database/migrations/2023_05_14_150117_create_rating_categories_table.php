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
        Schema::create('rating_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->boolean('online')->default(0);
            $table->timestamps();
        });

        Schema::create('category_rating_pull', function (Blueprint $table) {
            $table->foreignId('rating_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pull_id')->constrained()->cascadeOnDelete();
            $table->integer('rating');

            $table->primary(['rating_category_id', 'pull_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_rating_pull');
        Schema::dropIfExists('rating_categories');
    }
};
