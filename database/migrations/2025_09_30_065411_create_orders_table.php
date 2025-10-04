<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users table
            $table->string('customer_name');
            $table->decimal('total_amount', 10, 2)->default(0); // Store total amount
            $table->string('status')->default('pending'); // pending, processing, completed, cancelled
            $table->timestamps(); // This creates 'created_at' (purchase date) and 'updated_at'
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};