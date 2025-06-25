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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('department')->nullable()->after('phone');
            $table->string('position')->nullable()->after('department');
            $table->date('join_date')->nullable()->after('position');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('join_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'department', 'position', 'join_date', 'status']);
        });
    }
};
