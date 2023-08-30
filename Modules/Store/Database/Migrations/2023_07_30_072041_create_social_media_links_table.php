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
        Schema::create('social_media_links', function (Blueprint $table) {
            $table->id();
            $table->string('facebook')->nullable();
            $table->string('snapchat')->nullable();
            $table->string('twitter')->nullable();
            $table->string('tictok')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('maroof')->nullable();
            $table->string('instagram')->nullable();
            $table->string('telegram')->nullable();
            $table->string('google_play')->nullable();
            $table->string('app_store')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
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
        Schema::dropIfExists('social_media_links');
    }
};
