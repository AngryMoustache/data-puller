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
        Schema::table('pull_tag', function (Blueprint $table) {
            $table->string('group')->default('Main tags');
            $table->boolean('is_main')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pull_tag', function (Blueprint $table) {
            $table->boolean('is_main')->default(false);
            $table->dropColumn('group');
        });
    }
};
