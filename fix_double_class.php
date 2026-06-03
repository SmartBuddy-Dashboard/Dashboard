<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];
$files_modified = 0;

foreach ($folders as $folder) {
    $path = $base_dir . '/' . $folder . '/include/navbar.php';
    if (file_exists($path)) {
        $content = file_get_contents($path);

        // Fix the double class for Account Details
        $content = str_replace('class="px-3 py-3" class="dropdown-body-custom"', 'class="px-3 py-3 dropdown-body-custom"', $content);
        
        // Fix the double class for Logout Button
        $content = preg_replace('/class="([^"]*)"\s+href="logout.php"\s+data-toggle="modal"\s+data-target="#logoutModal"\s+class="dropdown-item-logout"/', 'class="$1 dropdown-item-logout" href="logout.php" data-toggle="modal" data-target="#logoutModal"', $content);

        if (file_put_contents($path, $content)) {
            echo "Fixed HTML in: $folder\n";
            $files_modified++;
        }
    }
}

echo "\nTotal navbars fixed: $files_modified\n";
?>
