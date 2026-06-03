<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];

$files_modified = 0;

$custom_css_add = "
/* Bulletproof modal text color for Dark Theme */
html[data-theme=\"dark\"] .modal-body {
    color: #ffffff !important;
}
html[data-theme=\"dark\"] .modal-body table,
html[data-theme=\"dark\"] .modal-body th,
html[data-theme=\"dark\"] .modal-body td {
    color: #ffffff !important;
    border-color: #4a5568 !important;
}
html[data-theme=\"dark\"] .modal-body h1,
html[data-theme=\"dark\"] .modal-body h2,
html[data-theme=\"dark\"] .modal-body h3,
html[data-theme=\"dark\"] .modal-body h4,
html[data-theme=\"dark\"] .modal-body h5,
html[data-theme=\"dark\"] .modal-body h6 {
    color: #ffffff !important;
}
";

foreach ($folders as $folder) {
    // 1. Fix header.php <script src="css/custom.css"></script>
    $header_path = $base_dir . '/' . $folder . '/include/header.php';
    if (file_exists($header_path)) {
        $content = file_get_contents($header_path);
        
        // Fix the script tag bug
        $content = str_replace('<script src="css/custom.css"></script>', '<link href="css/custom.css?v=<?= time() ?>" rel="stylesheet">', $content);
        
        file_put_contents($header_path, $content);
        echo "Fixed header in: $folder\n";
    }

    // 2. Add bulletproof CSS to custom.css
    $css_path = $base_dir . '/' . $folder . '/css/custom.css';
    if (file_exists($css_path)) {
        $content = file_get_contents($css_path);
        if (strpos($content, "Bulletproof modal text color") === false) {
            file_put_contents($css_path, $content . $custom_css_add);
            echo "Updated custom.css in: $folder\n";
        }
    }
}
?>
