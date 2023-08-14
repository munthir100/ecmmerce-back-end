<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku');
            $table->boolean('unspecified_quantity')->default(false);
            $table->integer('quantity')->nullable();
            $table->bigInteger('wheight')->nullable()->default(0);
            $table->string('short_description', 20)->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('price');
            $table->bigInteger('cost')->nullable();
            $table->boolean('is_discounted')->default(false);
            $table->bigInteger('price_after_discount')->nullable();
            $table->boolean('free_shipping')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_digital')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('category_id')
                ->nullable()
                ->references('id')
                ->on('categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('brand_id')
                ->nullable()
                ->references('id')
                ->on('brands')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('store_id')
                ->references('id')
                ->on('stores')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
