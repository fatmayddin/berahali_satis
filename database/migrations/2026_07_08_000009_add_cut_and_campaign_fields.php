<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_campaign')->default(false)->after('is_featured'); // Fırsatlar sayfası
            $table->boolean('is_cut')->default(false)->after('is_campaign'); // Kesme halı
            $table->decimal('cut_width_cm', 8, 2)->nullable()->after('is_cut'); // Sabit en (cm)
            $table->unsignedInteger('cut_min_cm')->default(100)->after('cut_width_cm'); // Min boy
            $table->unsignedInteger('cut_max_cm')->default(1500)->after('cut_min_cm'); // Max boy
            $table->decimal('total_price', 12, 2)->nullable()->change(); // Kesme halıda toplam fiyat yok
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('length_cm', 8, 2)->nullable()->after('m2'); // Kesme halı boyu
            $table->boolean('overlock')->default(false)->after('length_cm');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_campaign', 'is_cut', 'cut_width_cm', 'cut_min_cm', 'cut_max_cm']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['length_cm', 'overlock']);
        });
    }
};
