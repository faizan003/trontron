-- MySQL Optimization for TronLive on Hostinger Shared Hosting
-- Run these queries as database administrator

-- ===================================
-- BASIC PERFORMANCE OPTIMIZATIONS
-- ===================================

-- Enable query cache (if available)
SET GLOBAL query_cache_type = 1;
SET GLOBAL query_cache_size = 32M;
SET GLOBAL query_cache_limit = 2M;

-- Optimize connection settings
SET GLOBAL max_connections = 150;
SET GLOBAL connect_timeout = 10;
SET GLOBAL wait_timeout = 300;
SET GLOBAL interactive_timeout = 300;

-- ===================================
-- INNODB OPTIMIZATIONS
-- ===================================

-- Buffer pool optimization (adjust based on available RAM)
SET GLOBAL innodb_buffer_pool_size = 128M;

-- Log file settings
SET GLOBAL innodb_log_file_size = 64M;
SET GLOBAL innodb_log_buffer_size = 16M;

-- Transaction commit optimization
SET GLOBAL innodb_flush_log_at_trx_commit = 2;

-- File per table (better for maintenance)
SET GLOBAL innodb_file_per_table = 1;

-- Stats optimization
SET GLOBAL innodb_stats_on_metadata = 0;

-- ===================================
-- SLOW QUERY LOGGING
-- ===================================

-- Enable slow query log for monitoring
SET GLOBAL slow_query_log = 1;
SET GLOBAL long_query_time = 2;
SET GLOBAL log_queries_not_using_indexes = 1;

-- ===================================
-- TABLE SPECIFIC OPTIMIZATIONS
-- ===================================

-- Optimize the most frequently used tables
OPTIMIZE TABLE users;
OPTIMIZE TABLE stakings;
OPTIMIZE TABLE staking_plans;
OPTIMIZE TABLE wallets;
OPTIMIZE TABLE trx_transactions;

-- ===================================
-- INDEX ANALYSIS
-- ===================================

-- Check index usage (run after some traffic)
-- SELECT * FROM information_schema.STATISTICS WHERE table_schema = 'your_database_name';

-- ===================================
-- MONITORING QUERIES
-- ===================================

-- Check slow queries
-- SELECT * FROM mysql.slow_log ORDER BY start_time DESC LIMIT 10;

-- Check buffer pool hit ratio (should be >95%)
-- SELECT 
--   (1 - (Innodb_buffer_pool_reads / Innodb_buffer_pool_read_requests)) * 100 
--   AS buffer_pool_hit_ratio
-- FROM information_schema.GLOBAL_STATUS 
-- WHERE VARIABLE_NAME IN ('Innodb_buffer_pool_reads', 'Innodb_buffer_pool_read_requests');

-- Check query cache hit ratio
-- SELECT 
--   (Qcache_hits / (Qcache_hits + Qcache_inserts)) * 100 AS query_cache_hit_ratio
-- FROM information_schema.GLOBAL_STATUS 
-- WHERE VARIABLE_NAME IN ('Qcache_hits', 'Qcache_inserts');

-- ===================================
-- MAINTENANCE COMMANDS
-- ===================================

-- Run these periodically for maintenance
-- ANALYZE TABLE stakings;
-- ANALYZE TABLE users;
-- ANALYZE TABLE trx_transactions;

-- Check for fragmentation
-- SELECT 
--   table_name,
--   ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Table Size (MB)',
--   ROUND((data_free / 1024 / 1024), 2) AS 'Free Space (MB)'
-- FROM information_schema.TABLES 
-- WHERE table_schema = DATABASE()
-- ORDER BY (data_length + index_length) DESC; 