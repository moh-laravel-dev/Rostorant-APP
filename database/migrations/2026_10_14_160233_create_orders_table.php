<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('food_id')
                ->constrained('foods')
                ->cascadeOnDelete();

            $table->foreignId('bill_id')
                ->constrained('bills')
                ->cascadeOnDelete();

            $table->time('expected_time')->default('00:00:00');

            $table->enum('status', ['0', '1', '2'])->default('0');
            $table->unsignedInteger('amount')->default(0);
            $table->decimal('total_price', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
