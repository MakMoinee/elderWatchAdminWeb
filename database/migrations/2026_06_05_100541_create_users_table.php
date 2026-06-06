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
        Schema::create('users', function (Blueprint $table) {
            $table->id('userID')->autoIncrement();
            $table->string('email')->nullable(false);
            $table->string('firstName')->nullable(false);
            $table->string('middleName')->nullable();
            $table->string('lastName')->nullable(false);
            $table->string('address')->nullable();
            $table->string('password')->nullable(false);
            $table->string('phoneNumber')->nullable();
            $table->date('registeredDate')->useCurrent();
            $table->integer('userType')->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
