<?php
$file = __DIR__ . '/client/list_projectdetails.php';
$content = file_get_contents($file);

// Find the corrupted part
$search = "const now = new Date();\nconst timestamp = now.getFullYear() + String(now.getMonth() + 1).padStart(2, '0') + String(now.getDate()).padStart(2, '0') + '_' + String(now.getHours()).padStart(2, '0') + String(now.getMinutes()).padStart(2, '0');\n             \"<'row'<'col-md-6'l><'col-md-6 text-right'f>>\" +";

$replace = "
const now = new Date();
const timestamp = now.getFullYear() + String(now.getMonth() + 1).padStart(2, '0') + String(now.getDate()).padStart(2, '0') + '_' + String(now.getHours()).padStart(2, '0') + String(now.getMinutes()).padStart(2, '0');
const fileName = `Project_Details_${timestamp}`;

$(document).ready(function() {
    $('#userTable').DataTable({
        dom: \"<'row'<'col-md-12'B>>\" +
             \"<'row'<'col-md-6'l><'col-md-6 text-right'f>>\" +";

if (strpos($content, "const fileName = `Project_Details_\${timestamp}`;") === false) {
    // try a more generic replace if exact string is slightly off due to newlines
    $content = preg_replace('/const now = new Date\(\);.*?\"<\'row\'<\'col-md-6\'l><\'col-md-6 text-right\'f>>\" \+/s', $replace, $content);
    file_put_contents($file, $content);
    echo "Restored list_projectdetails.php\n";
} else {
    echo "Already restored or pattern not found.\n";
}
?>
