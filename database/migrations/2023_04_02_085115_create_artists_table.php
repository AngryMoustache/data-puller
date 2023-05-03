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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->foreignId('parent_id')->nullable()->constrained('artists')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('pulls', function (Blueprint $table) {
            $table->foreignId('artist_id')->after('artist')->nullable()->constrained('artists')->nullOnDelete();
            $table->dropColumn('artist');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pulls', function (Blueprint $table) {
            $table->string('artist')->after('artist_id');
            $table->dropForeign(['artist_id']);
            $table->dropColumn('artist_id');
        });

        Schema::dropIfExists('artists');
    }
};
