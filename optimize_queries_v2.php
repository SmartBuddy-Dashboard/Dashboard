<?php
// optimize_queries_v2.php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];
$replacements = 0;

foreach ($folders as $folder) {
    $dir = $base_dir . '/' . $folder;
    if (!is_dir($dir)) continue;

    $files = scandir($dir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $path = $dir . '/' . $file;
            $content = file_get_contents($path);
            $original = $content;

            // Simple str_replace for common patterns
            $content = str_replace('DATE(date_time)=CURDATE()', 'date_time >= CURDATE() AND date_time < (CURDATE() + INTERVAL 1 DAY)', $content);
            $content = str_replace('DATE(date_time) = CURDATE()', 'date_time >= CURDATE() AND date_time < (CURDATE() + INTERVAL 1 DAY)', $content);
            
            $content = str_replace("DATE(t.date_time) BETWEEN '\$from_date' AND '\$to_date'", "t.date_time >= '\$from_date 00:00:00' AND t.date_time <= '\$to_date 23:59:59'", $content);
            $content = str_replace("DATE(date_time) BETWEEN '\$from_date' AND '\$to_date'", "date_time >= '\$from_date 00:00:00' AND date_time <= '\$to_date 23:59:59'", $content);
            
            if ($content !== $original) {
                file_put_contents($path, $content);
                $replacements++;
                echo "Updated: $folder/$file\n";
            }
        }
    }
}
echo "Query optimization complete. Modified $replacements files.\n";
?>
