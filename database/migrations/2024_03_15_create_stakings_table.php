<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stakings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('staking_plans')->onDelete('cascade');
            $table->decimal('amount', 20, 6);
            $table->decimal('earned_amount', 20, 6)->default(0);
            $table->string('status')->default('active');
            $table->timestamp('staked_at');
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stakings');
    }
};
