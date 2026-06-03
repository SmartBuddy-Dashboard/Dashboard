<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];

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
    $css_path = $base_dir . '/' . $folder . '/css/custom.css';
    if (file_exists($css_path)) {
        $content = file_get_contents($css_path);
        if (strpos($content, "dropdown-body-custom") === false) {
            file_put_contents($css_path, $content . $css_add);
            echo "Updated custom.css in: $folder\n";
        }
    }
}
?>
