<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('withdrawal_history', function (Blueprint $table) {
            $table->decimal('original_amount', 18, 6)->after('amount');
            $table->decimal('fee', 18, 6)->after('original_amount');
        });
    }

    public function down()
    {
        Schema::table('withdrawal_history', function (Blueprint $table) {
            $table->dropColumn(['original_amount', 'fee']);
        });
    }
};
