<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom pemilik task + backfill data lama (pemilik = pemilik workspace).
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('created_by')
                ->after('list_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

        // SQL statis tanpa input user — aman. Task lama menyerap pemilik workspace-nya.
        DB::statement('UPDATE tasks t JOIN lists l ON l.id = t.list_id SET t.created_by = l.owner_id WHERE t.created_by IS NULL');
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
        });
    }
};