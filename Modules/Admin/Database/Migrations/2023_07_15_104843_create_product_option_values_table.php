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
        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('additional_price', 8, 2)->default(0);
            $table->integer('quantity')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreignId('product_option_id')->references('id')
                ->on('product_options')
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
        Schema::dropIfExists('option_values');
    }
};
