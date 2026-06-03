-- Enterprise Scalability: Database Optimization Indexes
-- Run this script in your Laragon/MySQL to drastically improve dashboard loading times.

-- 1. Optimize Login Queries (Speeds up index.php)
ALTER TABLE `tblusers` ADD INDEX `idx_mobile_status` (`mobile`, `status`);
ALTER TABLE `clients` ADD INDEX `idx_contact_mobile` (`contact_mobile`);

-- 2. Optimize IoT Data Retrieval
-- Since datatest gets millions of rows, indexing by machine_id and time is critical for fast graph rendering.
ALTER TABLE `datatest` ADD INDEX `idx_machine_time` (`machine_id`, `received_time`);

-- 3. Optimize Project/Machine Dashboard Joins
-- The query in list_projectdetails.php uses: WHERE m.client_name = '$client_name'
ALTER TABLE `machines` ADD INDEX `idx_client_name` (`client_name`);
ALTER TABLE `projects` ADD INDEX `idx_project_name` (`project_name`);

-- Note on Security Upgrade:
-- To migrate away from plain-text passwords, you should run a PHP script that reads all current passwords, 
-- hashes them using password_hash($pass, PASSWORD_BCRYPT), and updates the DB.
-- After that, update index.php to use:
-- $user = $result->fetch_assoc();
-- if(password_verify($password, $user['password'])) { ... }
