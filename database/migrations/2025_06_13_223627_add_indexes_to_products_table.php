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
        Schema::table('products', function (Blueprint $table) {
            // Simple indexes for searches and sorting
            $table->index('price');
            $table->index('stock');
            $table->index('is_active');

            // Composite index for common searches
            $table->index(['name', 'is_active']);
            
            // Regular index for description searches
            $table->index('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['price']);
            $table->dropIndex(['stock']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['name', 'is_active']);
            $table->dropIndex(['description']);
        });
    }
}; 