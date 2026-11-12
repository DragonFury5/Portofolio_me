<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 40)->unique();
            $table->string('title', 100);
            $table->string('description', 220)->default('');
            $table->string('mode', 16)->default('template');
            $table->string('template', 24)->nullable();
            $table->string('accent', 16)->default('blue');
            $table->json('content')->nullable();
            $table->longText('html')->nullable();
            $table->longText('css')->nullable();
            $table->longText('js')->nullable();
            $table->boolean('is_published')->default(false)->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_published', 'updated_at']);
        });

        Schema::create('portfolio_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->date('day');
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();

            $table->unique(['portfolio_id', 'day']);
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->string('reason', 60);
            $table->text('details')->nullable();
            $table->string('reporter_email', 190)->nullable();
            $table->string('status', 20)->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
        Schema::dropIfExists('portfolio_views');
        Schema::dropIfExists('portfolios');
    }
};
