<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->integer('quantity_change');

            $table->enum('type', ['IN', 'OUT', 'MOVE']);

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('user_id')->references('id')->on('users');

            $table->index('product_id');
            $table->index('location_id');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
