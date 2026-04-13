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
        Schema::create('scraper_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('kamis');
            $table->boolean('success');
            $table->integer('products_found')->default(0);
            $table->integer('products_scraped')->default(0);
            $table->integer('rows_saved')->default(0);
            $table->integer('rows_processed')->default(0);
            $table->text('errors')->nullable(); // JSON
            $table->integer('duration_seconds')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraper_logs');
    }
};
