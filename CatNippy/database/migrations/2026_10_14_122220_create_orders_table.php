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

            $table->string('order_number')->unique();

            // Nullable to support guest checkout.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Customer details.
            $table->string('customer_name');
            $table->string('email');
            $table->string('phone')->nullable();

            // Shipping address snapshot.
            $table->string('address');
            $table->string('city');
            $table->string('postal_code', 20);
            $table->string('country', 100);

            // Monetary amounts, stored in EUR.
            $table->string('currency', 3)->default('EUR');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('total', 10, 2);

            // Order and simulated payment state.
            $table->string('status')->default('pending');
            $table->string('payment_status')->default('pending');

            $table->timestamps();

            $table->index(['email', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
