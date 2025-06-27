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
        // Add indexes for better vault performance (only if they don't exist)
        
        // Check and add wallets indexes
        if (!$this->indexExists('wallets', 'wallets_user_id_index')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->index('user_id', 'wallets_user_id_index');
            });
        }
        
        if (!$this->indexExists('wallets', 'wallets_created_at_index')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->index('created_at', 'wallets_created_at_index');
            });
        }
        
        // Check and add withdrawal_history indexes
        if (!$this->indexExists('withdrawal_history', 'withdrawal_history_user_status_index')) {
            Schema::table('withdrawal_history', function (Blueprint $table) {
                $table->index(['user_id', 'status'], 'withdrawal_history_user_status_index');
            });
        }
        
        if (!$this->indexExists('withdrawal_history', 'withdrawal_history_created_at_index')) {
            Schema::table('withdrawal_history', function (Blueprint $table) {
                $table->index('created_at', 'withdrawal_history_created_at_index');
            });
        }
        
        // Check and add stakings indexes
        if (!$this->indexExists('stakings', 'stakings_user_status_index')) {
            Schema::table('stakings', function (Blueprint $table) {
                $table->index(['user_id', 'status'], 'stakings_user_status_index');
            });
        }
        
        if (!$this->indexExists('stakings', 'stakings_created_at_index')) {
            Schema::table('stakings', function (Blueprint $table) {
                $table->index('created_at', 'stakings_created_at_index');
            });
        }
        
        // Check and add users indexes
        if (!$this->indexExists('users', 'users_email_search_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('email', 'users_email_search_index');
            });
        }
        
        if (!$this->indexExists('users', 'users_phone_search_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('phone', 'users_phone_search_index');
            });
        }
        
        if (!$this->indexExists('users', 'users_created_at_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('created_at', 'users_created_at_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropIndex('wallets_user_id_index');
            $table->dropIndex('wallets_created_at_index');
        });
        
        Schema::table('withdrawal_history', function (Blueprint $table) {
            $table->dropIndex('withdrawal_history_user_status_index');
            $table->dropIndex('withdrawal_history_created_at_index');
        });
        
        Schema::table('stakings', function (Blueprint $table) {
            $table->dropIndex('stakings_user_status_index');
            $table->dropIndex('stakings_created_at_index');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_email_search_index');
            $table->dropIndex('users_phone_search_index');
            $table->dropIndex('users_created_at_index');
        });
    }
    
    /**
     * Check if an index exists on a table
     */
    private function indexExists($table, $indexName)
    {
        $connection = Schema::getConnection();
        $databaseName = $connection->getDatabaseName();
        
        $result = $connection->select("
            SELECT COUNT(*) as count 
            FROM information_schema.statistics 
            WHERE table_schema = ? 
            AND table_name = ? 
            AND index_name = ?
        ", [$databaseName, $table, $indexName]);
        
        return $result[0]->count > 0;
    }
}; 