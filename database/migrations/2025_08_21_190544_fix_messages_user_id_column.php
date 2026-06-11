<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Add user_id if it's missing
        Schema::table('messages', function (Blueprint $table) {
            if (! Schema::hasColumn('messages', 'user_id')) {
                // make it nullable first to avoid issues while we backfill
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('conversation_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }
        });

        // 2) If we have sender_id, copy values into user_id
        if (Schema::hasColumn('messages', 'sender_id') && Schema::hasColumn('messages', 'user_id')) {
            DB::statement('UPDATE messages SET user_id = sender_id WHERE user_id IS NULL');
        }

        // 3) Drop sender_id (and its FK) if it exists
        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'sender_id')) {
                // drops FK and column (Laravel will guess the FK name)
                $table->dropConstrainedForeignId('sender_id');
            }
        });

        // 4) Ensure read_at exists (if you didn’t add it yet)
        Schema::table('messages', function (Blueprint $table) {
            if (! Schema::hasColumn('messages', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('body');
            }
        });
    }

    public function down(): void
    {
        // Reverse: re-add sender_id (nullable), copy back, then drop user_id
        Schema::table('messages', function (Blueprint $table) {
            if (! Schema::hasColumn('messages', 'sender_id')) {
                $table->foreignId('sender_id')
                    ->nullable()
                    ->after('conversation_id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }
        });

        if (Schema::hasColumn('messages', 'sender_id') && Schema::hasColumn('messages', 'user_id')) {
            DB::statement('UPDATE messages SET sender_id = user_id WHERE sender_id IS NULL');
        }

        Schema::table('messages', function (Blueprint $table) {
            if (Schema::hasColumn('messages', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (Schema::hasColumn('messages', 'read_at')) {
                $table->dropColumn('read_at');
            }
        });
    }
};
