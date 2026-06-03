<?php
$files = [
    'client/list_projectdetails.php',
    'client/machine_detailsuser.php',
    'client/report_usermachinedetails.php',
    'client/report_detailsclient.php'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $original = $content;

        // Add JS variables if missing and DataTable is used
        if (strpos($content, '$(document).ready(function() {') !== false && strpos($content, 'const fileName =') === false) {
            $js_vars = "
const now = new Date();
const timestamp = now.getFullYear() + String(now.getMonth() + 1).padStart(2, '0') + String(now.getDate()).padStart(2, '0') + '_' + String(now.getHours()).padStart(2, '0') + String(now.getMinutes()).padStart(2, '0');
const fileName = 'Report-' + timestamp;
\$(document).ready(function() {";
            $content = str_replace('$(document).ready(function() {', $js_vars, $content);
        }

        if ($content !== $original) {
            file_put_contents($path, $content);
            echo "Fixed JS in $file<br>";
        }
    }
}
echo "Done JS fixing.";
?>
