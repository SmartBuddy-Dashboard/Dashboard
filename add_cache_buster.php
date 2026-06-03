<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];

$files_modified = 0;

foreach ($folders as $folder) {
    $path = $base_dir . '/' . $folder . '/include/header.php';
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Add ?v=1 time based cache buster to theme.css
        $content = str_replace('href="css/theme.css"', 'href="css/theme.css?v=<?= time() ?>"', $content);
        $content = str_replace('href="../assets/css/premium-industrial.css"', 'href="../assets/css/premium-industrial.css?v=<?= time() ?>"', $content);
        
        // In case it already has a static version like ?v=2
        $content = preg_replace('/href="css\/theme\.css\?v=[^"]*"/', 'href="css/theme.css?v=<?= time() ?>"', $content);
        $content = preg_replace('/href="\.\.\/assets\/css\/premium-industrial\.css\?v=[^"]*"/', 'href="../assets/css/premium-industrial.css?v=<?= time() ?>"', $content);
        
        file_put_contents($path, $content);
        echo "Updated $path\n";
        $files_modified++;
    }
}

echo "\nTotal headers updated: $files_modified\n";
?>
