<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            // Many-to-One: inventory belongs to one product
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            // Many-to-One: inventory belongs to one branch
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('low_stock_alert')->default(5);
            $table->timestamps();

            // One product can only have one inventory record per branch
            $table->unique(['product_id', 'branch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
