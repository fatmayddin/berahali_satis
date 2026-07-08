<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique(); // ürün kodu
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('size_text')->nullable(); // ör: 120x180 cm
            $table->decimal('m2', 8, 2)->nullable(); // metrekare
            $table->decimal('price_per_m2', 12, 2)->nullable(); // m2 başına fiyat
            $table->decimal('total_price', 12, 2); // toplam fiyat
            $table->decimal('discount_price', 12, 2)->nullable(); // indirimli fiyat
            $table->longText('description')->nullable();
            $table->json('features')->nullable(); // ürün özellikleri (ad/değer)
            $table->string('cover_image')->nullable();
            $table->unsignedInteger('stock')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
