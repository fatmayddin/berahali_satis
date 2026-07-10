<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('ip', 45);
            $table->string('path', 191);
            $table->unsignedInteger('hits')->default(1);
            $table->timestamps();

            $table->unique(['date', 'ip', 'path']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
