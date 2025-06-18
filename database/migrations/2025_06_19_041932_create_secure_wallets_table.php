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
        Schema::create('secure_wallets', function (Blueprint $table) {
            $table->id();
            $table->string('wallet_type'); // 'admin', 'hot', 'cold'
            $table->text('encrypted_address'); // Encrypted wallet address
            $table->text('encrypted_private_key'); // Encrypted private key
            $table->string('key_hash'); // Hash for verification without decryption
            $table->decimal('balance_limit', 20, 6)->default(0); // Max balance allowed
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            
            // Security indexes
            $table->index(['wallet_type', 'is_active']);
            $table->unique(['wallet_type', 'key_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('secure_wallets');
    }
};
