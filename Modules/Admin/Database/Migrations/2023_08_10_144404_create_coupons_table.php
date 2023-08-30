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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('promocode')->unique();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('value', 10, 2);
            $table->timestamp('discount_end_date');
            $table->boolean('exclude_discounted_products')->default(false);
            $table->decimal('minimum_purchase', 10, 2);
            $table->unsignedInteger('total_usage_times');
            $table->unsignedInteger('usage_per_customer');
            $table->unsignedInteger('used_times')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('coupons');
    }
};
