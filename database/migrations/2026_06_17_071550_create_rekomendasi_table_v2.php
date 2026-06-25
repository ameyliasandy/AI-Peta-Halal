<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke users (id = bigint unsigned)
            $table->unsignedBigInteger('user_id');
            
            // Foreign key ke restoran (id_restoran = int unsigned)
            $table->unsignedInteger('id_restoran');
            
            $table->decimal('score', 8, 4);
            $table->integer('rank')->default(1);
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('id_restoran')
                  ->references('id_restoran')
                  ->on('restoran')
                  ->onDelete('cascade');
            
            // Biar ga duplikat
            $table->unique(['user_id', 'id_restoran']);
            
            // Index untuk query cepat
            $table->index(['user_id', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi');
    }
};