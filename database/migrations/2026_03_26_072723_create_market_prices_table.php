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
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('commodity_name');       // e.g. "Dry Maize"
            $table->string('classification')->nullable(); // e.g. "White Maize"
            $table->string('market');               // e.g. "Kawangware"
            $table->decimal('wholesale_price', 10, 2)->nullable();
            $table->decimal('retail_price', 10, 2)->nullable();
            $table->string('unit', 20)->default('Kg'); // extracted from price string
            $table->decimal('supply_volume', 12, 2)->nullable();
            $table->string('county');
            $table->date('price_date');
            $table->string('source')->default('kamis');
            $table->timestamps();

            $table->index(['commodity_name', 'county', 'price_date']);
            $table->index('price_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
