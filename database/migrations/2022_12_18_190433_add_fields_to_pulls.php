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
        Schema::table('pulls', function (Blueprint $table) {
            $table->after('status', function (Blueprint $table) {
                $table->foreignId('preview_id')->nullable()->constrained('attachments')->onDelete('set null');
                $table->boolean('comic')->default(false);
            });
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
            //
        });
    }
};
