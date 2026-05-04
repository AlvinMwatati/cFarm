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
        Schema::create('commodity_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('commodity_id')->constrained('commodities')->onDelete('cascade');

            // Notification preferences per followed commodity
            $table->boolean('notify_price_drop')->default(true);
            $table->boolean('notify_price_spike')->default(true);
            $table->boolean('notify_new_listing')->default(true);
            $table->boolean('notify_weekly_summary')->default(true);

            // Delivery channels
            $table->boolean('via_app')->default(true);
            $table->boolean('via_email')->default(true);
            $table->boolean('via_sms')->default(false);

            // Threshold — percentage change that triggers alert (default 10%)
            $table->decimal('price_change_threshold', 5, 2)->default(10.00);

            $table->timestamps();

             // Unique constraint to prevent duplicate follows
            $table->unique(['user_id', 'commodity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commodity_follows');
    }
};
