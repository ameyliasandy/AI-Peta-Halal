<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id('id_ulasan');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('id_restoran');
            $table->foreign('id_restoran')
                  ->references('id_restoran')
                  ->on('restoran')
                  ->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('komentar')->nullable();
            $table->timestamps();

            // 1 user cuma bisa kasih 1 rating per resto (bisa update kalau mau ganti)
            $table->unique(['user_id', 'id_restoran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};