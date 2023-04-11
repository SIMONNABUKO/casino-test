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
        Schema::create('casino_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('title');
            $table->string('gamebank');
            $table->string('device');
            $table->string('label')->nullable();
            $table->string('shop_id');
            $table->string('bet')->nullable();
            $table->string('original_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casino_games');
    }
};
