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
    Schema::table('posts', function (Blueprint $table) {
        if (!Schema::hasColumn('posts', 'user_id')) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
        }
        if (!Schema::hasColumn('posts', 'title')) {
            $table->string('title')->after('user_id');
        }
        if (!Schema::hasColumn('posts', 'content')) {
            $table->text('content')->after('title');
        }
        if (!Schema::hasColumn('posts', 'attachment')) {
            $table->json('attachment')->nullable()->after('content'); // or text()
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            //
        });
    }
};
