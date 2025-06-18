<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stakings', function (Blueprint $table) {
            $table->index(['status', 'last_reward_at']);
            $table->index(['user_id', 'status']);
            $table->index('staked_at');
            $table->index('end_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('referral_code');
            $table->index('referred_by');
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('address');
        });

        Schema::table('referral_earnings', function (Blueprint $table) {
            $table->index(['referrer_id', 'status']);
            $table->index('referred_id');
        });

        Schema::table('trx_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'type']);
            $table->index('transaction_id');
        });
    }

    public function down()
    {
        Schema::table('stakings', function (Blueprint $table) {
            $table->dropIndex(['status', 'last_reward_at']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['staked_at']);
            $table->dropIndex(['end_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['referral_code']);
            $table->dropIndex(['referred_by']);
        });

        Schema::table('wallets', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['address']);
        });

        Schema::table('referral_earnings', function (Blueprint $table) {
            $table->dropIndex(['referrer_id', 'status']);
            $table->dropIndex(['referred_id']);
        });

        Schema::table('trx_transactions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'type']);
            $table->dropIndex(['transaction_id']);
        });
    }
}; 