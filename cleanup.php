<?php
$base_dir = __DIR__;
$folders = ['client', 'operation'];

foreach ($folders as $folder) {
    $dir = $base_dir . '/' . $folder;
    if (!is_dir($dir)) continue;

    $files = scandir($dir);
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $filepath = $dir . '/' . $file;
            $content = file_get_contents($filepath);
            $original = $content;

            // Remove session_start() if navbar is included
            if (strpos($content, 'include/navbar.php') !== false || strpos($content, "include('navbar.php')") !== false || strpos($content, 'include "navbar.php"') !== false || strpos($content, "require_once('include/navbar.php')") !== false || strpos($content, 'include("include/navbar.php")') !== false) {
                // remove session_start(); anywhere in the file
                $content = preg_replace('/^\s*session_start\(\)\s*;.*$/m', '', $content);
            }

            // Remove hardcoded backgrounds
            $content = preg_replace('/background\s*:\s*#dbe5ec;?/i', '', $content);
            $content = preg_replace('/background\s*:\s*#fbdede;?/i', '', $content);
            $content = preg_replace('/style=[\'"]\s*border:1px solid #ddd;?[\'"]/i', '', $content);

            if ($content !== $original) {
                file_put_contents($filepath, $content);
                echo "Cleaned up: $filepath\n<br>";
            }
        }
    }
}
echo "Cleanup complete!\n";
?>
