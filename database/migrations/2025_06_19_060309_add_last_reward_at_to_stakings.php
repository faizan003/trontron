<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stakings', function (Blueprint $table) {
            $table->timestamp('last_reward_at')->nullable()->after('end_at');
        });
    }

    public function down()
    {
        Schema::table('stakings', function (Blueprint $table) {
            $table->dropColumn('last_reward_at');
        });
    }
};
