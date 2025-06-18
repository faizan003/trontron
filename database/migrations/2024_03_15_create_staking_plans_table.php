<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('staking_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('minimum_amount', 20, 6);
            $table->decimal('maximum_amount', 20, 6)->default(0);
            $table->decimal('interest_rate', 8, 2); // Daily interest rate percentage
            $table->integer('duration')->default(60); // Duration in days
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('staking_plans');
    }
};
