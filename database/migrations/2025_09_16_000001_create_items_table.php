<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 40)->unique();
            $table->string('name', 120);
            // min_stock: Mindestbestand für Materialien oder NULL für Produkte
            $table->unsignedInteger('min_stock')->nullable();
            // target_stock: Zielbestand für Fertigprodukte (z. B. Modulix), sonst NULL
            $table->unsignedInteger('target_stock')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};