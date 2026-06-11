<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('faculty')->nullable();
            $table->string('semester')->nullable();
            $table->string('student_id')->nullable();
            $table->string('profile_picture')->nullable(); // File path
            $table->text('bio')->nullable();
            $table->string('role')->default('user'); // You will manually set to 'admin' if needed
            $table->string('department')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'faculty', 'semester', 'student_id', 'profile_picture',
                'bio', 'role', 'department', 'phone_number', 'date_of_birth'
            ]);
        });
    }
};
