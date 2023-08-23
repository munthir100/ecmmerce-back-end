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
            $table->string('commercial_registration_no')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('maintenance_message')->nullable();


            $table->string('button_color')->default('#7367F0');
            $table->string('text_color')->default('#7367F0');
            $table->string('banner_color')->default('#7367F0');
            $table->string('banner_content')->nullable();
            $table->string('banner_link')->nullable();



            $table->foreignId('language_id')
                ->constrained('languages');


            $table->foreignId('admin_id')
                ->references('id')
                ->on('admins')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('store_theme_id')
                ->constrained('store_themes');

            $table->foreignId('city_id')
                ->nullable()
                ->constrained('cities');
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
