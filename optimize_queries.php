<?php
// optimize_queries.php
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

            // 1. Replace DATE(date_time) = CURDATE()
            $content = preg_replace(
                '/DATE\(\s*(t\.)?date_time\s*\)\s*=\s*CURDATE\(\)/i', 
                '$1date_time >= CURDATE() AND $1date_time < (CURDATE() + INTERVAL 1 DAY)', 
                $content
            );

            // 2. Replace DATE(t.date_time) BETWEEN '$from_date' AND '$to_date'
            $content = preg_replace(
                '/DATE\(\s*(t\.)?date_time\s*\)\s*BETWEEN\s*\'\$from_date\'\s*AND\s*\'\$to_date\'/i', 
                '$1date_time >= \'$from_date 00:00:00\' AND $1date_time <= \'$to_date 23:59:59\'', 
                $content
            );

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
