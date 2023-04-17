<?php

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
        Schema::create('user_location', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('city_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('city')->onDelete('cascade');
            $table->float('lat')->nullable();
            $table->float('long')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table->dropForeign('user_city_user_id_foreign');
        $table->dropForeign('user_city_city_id_foreign');
        Schema::dropIfExists('user_location');
    }
};
