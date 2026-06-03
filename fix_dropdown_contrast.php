<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];
$files_modified = 0;

foreach ($folders as $folder) {
    $path = $base_dir . '/' . $folder . '/include/navbar.php';
    if (file_exists($path)) {
        $content = file_get_contents($path);

        // Fix the Account Details background
        $content = str_replace('style="background-color: var(--card-bg, #ffffff);"', 'class="dropdown-body-custom"', $content);
        
        // Fix the User ID text color
        $content = str_replace('color: #2c3e50;', 'color: var(--text-main);', $content);
        
        // Fix the User ID label color
        $content = str_replace('color: #7f8c8d;', 'color: var(--text-muted);', $content);
        
        // Fix the Logout button background and text color
        $content = str_replace('style="background-color: #fffafb; transition: all 0.2s;"', 'class="dropdown-item-logout"', $content);

        if (file_put_contents($path, $content)) {
            echo "Fixed navbar HTML in: $folder\n";
            $files_modified++;
        }
    }
}

// Now append the missing CSS for these classes to fix_dark_theme or theme.css
$css_add = "
/* Custom Dropdown Styling for Profile */
.dropdown-body-custom {
    background-color: var(--bg-card) !important;
}
.dropdown-item-logout {
    transition: all 0.2s;
    background-color: transparent !important;
    color: #e74a3b !important;
}
html[data-theme=\"dark\"] .dropdown-item-logout {
    color: #ff6b6b !important;
}
html[data-theme=\"dark\"] .dropdown-item-logout:hover {
    background-color: rgba(231, 74, 59, 0.1) !important;
}
";

foreach ($folders as $folder) {
    $css_path = $base_dir . '/' . $folder . '/css/theme.css';
    if (file_exists($css_path)) {
        $content = file_get_contents($css_path);
        if (strpos($content, "dropdown-body-custom") === false) {
            file_put_contents($css_path, $content . $css_add);
            echo "Updated theme.css in: $folder\n";
        }
    }
}

echo "\nTotal navbars updated: $files_modified\n";
?>
