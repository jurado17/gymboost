<?php

use App\Models\User;
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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->foreignId('weight_id')->references('id')->on('weights')->nullable();
            $table->foreignId('flavour_id')->references('id')->on('flavours')->nullable();
            $table->integer('quantity');
            $table->decimal('final_price',10,2);
            $table->foreignId('promotion_id')->references('id')->on('promotions')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
