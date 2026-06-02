<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('email');
            $table->string('no_telepon', 20)->nullable()->after('foto_profil');
            $table->boolean('notif_promo')->default(true)->after('no_telepon');
            $table->boolean('notif_ulasan')->default(true)->after('notif_promo');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['foto_profil', 'no_telepon', 'notif_promo', 'notif_ulasan']);
        });
    }
};