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
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name', 16);
            $table->string('last_name', 16);
            $table->integer('phone_num')->nullable();
            $table->integer('phone_code')->nullable();
            $table->string('country', 32)->nullable();
            $table->string('address', 64)->nullable();
            $table->string('city', 16)->nullable();
            $table->string('zip', 7)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_data');
    }
};
