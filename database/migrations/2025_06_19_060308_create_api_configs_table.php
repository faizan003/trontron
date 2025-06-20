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
        Schema::create('api_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key_name')->unique(); // e.g., 'trongrid_api_key'
            $table->text('encrypted_value'); // Encrypted API key
            $table->string('network')->default('testnet'); // testnet or mainnet
            $table->string('api_url')->default('https://nile.trongrid.io');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_configs');
    }
};
