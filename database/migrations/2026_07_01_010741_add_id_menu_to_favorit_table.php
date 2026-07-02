<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorit', function (Blueprint $table) {

            $table->unsignedInteger('id_menu')
                  ->nullable()
                  ->after('id_restoran');

            $table->foreign('id_menu')
                  ->references('id_menu')
                  ->on('menu')
                  ->onDelete('cascade');

        });
    }


    public function down(): void
    {
        Schema::table('favorit', function (Blueprint $table) {

            $table->dropForeign(['id_menu']);

            $table->dropColumn('id_menu');

        });
    }
};