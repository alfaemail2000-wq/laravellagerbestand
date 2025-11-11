<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['in', 'out', 'transfer']);
            $table->foreignId('from_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('to_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('note', 255)->nullable();
            $table->timestamps();

            // sinnvolle Indizes
            $table->index(['item_id', 'type']);
            $table->index(['from_location_id']);
            $table->index(['to_location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
