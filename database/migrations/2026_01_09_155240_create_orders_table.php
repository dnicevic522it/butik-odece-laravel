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
        Schema::disableForeignKeyConstraints();

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('user_id')->constrained();
            $table->enum('status', ["pending","processing","shipped","delivered","cancelled"])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->string('shipping_address', 255);
            $table->string('shipping_city', 100);
            $table->string('shipping_postal_code', 20);
            $table->enum('payment_method', ["cash_on_delivery","card","paypal"])->default('cash_on_delivery');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
