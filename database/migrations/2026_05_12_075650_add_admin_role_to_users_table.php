<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pencari', 'pemilik_usaha', 'admin') NOT NULL DEFAULT 'pencari'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pencari', 'pemilik_usaha') NOT NULL DEFAULT 'pencari'");
    }
};