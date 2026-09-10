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
        Schema::create('uuid_histories', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();

            $table->string('type', 50);

            $table->string('version', 20);

            $table->timestamp('generated_at')->useCurrent();

            $table->timestamps();

            $table->index('type');
            $table->index('version');
            $table->index('generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uuid_histories');
    }
};