<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 24)->nullable()->unique()->after('name');
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('location', 100)->nullable();
            $table->string('website', 190)->nullable();
            $table->boolean('is_admin')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['username', 'bio', 'avatar', 'location', 'website', 'is_admin']);
        });
    }
};
