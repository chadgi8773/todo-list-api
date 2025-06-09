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
        Schema::table('note_user', function (Blueprint $table) {
            $table->dropForeign(['note_id']);
            $table->foreign('note_id')
                  ->references('id')
                  ->on('notes')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('note_user', function (Blueprint $table) {
            $table->dropForeign(['note_id']);
            $table->foreign('note_id')
                  ->references('id')
                  ->on('notes');
        });
    }
};
