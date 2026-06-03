<?php
// apply_indexes.php
require_once('client/include/config.php');

$queries = [
    "ALTER TABLE `trans` ADD INDEX `idx_date_time` (`date_time`);",
    "ALTER TABLE `trans` ADD INDEX `idx_machine_status_date` (`machin_id`, `status`, `date_time`);",
    "ALTER TABLE `trans` ADD INDEX `idx_status_date` (`status`, `date_time`);",
    "ALTER TABLE `datatest` ADD INDEX `idx_machine_time` (`machine_id`, `received_time`);",
    "ALTER TABLE `machines` ADD INDEX `idx_client_name` (`client_name`);",
    "ALTER TABLE `projects` ADD INDEX `idx_project_name` (`project_name`);",
    "ALTER TABLE `tblusers` ADD INDEX `idx_mobile_status` (`mobile`, `status`);"
];

echo "Applying indexes for 1,000+ toilets scalability...\n";

foreach ($queries as $q) {
    echo "Running: $q\n";
    if (mysqli_query($conn, $q)) {
        echo "SUCCESS: Index added.\n";
    } else {
        echo "INFO: " . mysqli_error($conn) . " (Might already exist)\n";
    }
    echo "---------------------------\n";
}
echo "Done.\n";
?>
