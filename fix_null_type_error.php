<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];
$files_modified = 0;

foreach ($folders as $folder) {
    $dir = $base_dir . '/' . $folder;
    if (!is_dir($dir)) continue;

    $files = scandir($dir);
    foreach ($files as $file) {
        if (strpos($file, 'view_') === 0 && strpos($file, '.php') !== false) {
            $path = $dir . '/' . $file;
            $content = file_get_contents($path);

            // Replace htmlspecialchars($var) with htmlspecialchars($var ?? '')
            $new_content = preg_replace('/htmlspecialchars\(\s*(\$[a-zA-Z0-9_]+)\s*\)/', 'htmlspecialchars($1 ?? \'\')', $content);

            if ($content !== $new_content) {
                file_put_contents($path, $new_content);
                echo "Fixed null TypeError in: $folder/$file\n";
                $files_modified++;
            }
        }
    }
}

echo "\nTotal view files fixed: $files_modified\n";
?>
