<?php

use App\Enums\KenyaCounty;
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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            // Who posted it
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // What commodity is being sold
            $table->foreignId('commodity_id')
                  ->constrained('commodities')
                  ->restrictOnDelete();

            $table->string('title');
            $table->text('description')->nullable();

            // Pricing & quantity
            $table->decimal('price_per_unit', 10, 2); // e.g 3500.00
            $table->integer('quantity_available');
            $table->integer('minimum_order_quantity');

            // Location
            $table->enum('county', KenyaCounty::values());
            $table->string('town')->nullable(); // more specific location

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
