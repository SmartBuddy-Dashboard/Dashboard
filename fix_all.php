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

            // 1. Remove pink odd-row backgrounds even if they have !important
            $content = preg_replace('/background\s*:\s*#fbdede[^;]*;?/i', '', $content);

            // 2. Add missing JS variables for DataTables if it uses fileName but hasn't defined it
            if (strpos($content, 'filename: fileName') !== false && strpos($content, 'const fileName =') === false && strpos($content, 'let fileName =') === false) {
                // Find where $(document).ready is and inject the variables just before it
                $js_vars = "
const now = new Date();
const timestamp = now.getFullYear() + String(now.getMonth() + 1).padStart(2, '0') + String(now.getDate()).padStart(2, '0') + '_' + String(now.getHours()).padStart(2, '0') + String(now.getMinutes()).padStart(2, '0');
const fileName = `Report-${timestamp}`;
";
                $content = str_replace('$(document).ready(function() {', $js_vars . "\n$(document).ready(function() {", $content);
            }

            // 3. Remove stat card white backgrounds in report_detailsclient.php
            if ($file === 'report_detailsclient.php' || $file === 'report_detailsclient_Old.php') {
                $content = preg_replace('/\.kpi-grid\s*\{[^\}]*background\s*:\s*white;?/', '.kpi-grid { background: transparent;', $content);
                $content = preg_replace('/\.kpi-card\s*\{[^\}]*background\s*:\s*#f3f7fc;?/', '.kpi-card { background: var(--bg-card);', $content);
                
                // Also fix table headers if they have background white
                $content = preg_replace('/background\s*:\s*#fff;?/', '', $content);
                $content = preg_replace('/background\s*:\s*white;?/', '', $content);
            }

            if ($content !== $original) {
                file_put_contents($filepath, $content);
                echo "Cleaned up: $filepath\n<br>";
            }
        }
    }
}
echo "Fix complete!\n";
?>
