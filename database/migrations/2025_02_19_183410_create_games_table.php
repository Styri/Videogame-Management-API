<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150)->unique();
            $table->text('description');
            $table->date('release_date');
            $table->string('genre', 50);
            $table->boolean('is_single_player')->default(true);
            $table->boolean('is_multi_player')->default(false);
            $table->string('publisher', 75);
            $table->string('developer', 75);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
