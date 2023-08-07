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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->string('default_currency');
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('admin_id')
                ->references('id')
                ->on('admins')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('store_theme_id')
                ->nullable()
                ->references('id')
                ->on('store_themes')
                ->nullOnDelete()
                ->default(1);

            $table->foreignId('city_id')
                ->nullable()
                ->references('id')
                ->on('cities')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
};
