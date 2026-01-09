<?php

use App\Enums\StatusEnum;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug', 500)->unique();
            $table->string('thumbnail')->nullable();
            $table->text('images')->nullable();
            $table->float('unit_price', 8, 2)->default(0);
            $table->string('sku', 100)->nullable();
            $table->float('discount', 8, 2)->default(0);
            $table->string('discount_type')->default('fixed')->comment('fixed, percent');
            $table->longText('description')->nullable();
            $table->integer('current_stock')->default(0);
            $table->unsignedTinyInteger('status')->default(StatusEnum::INACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
