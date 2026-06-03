<?php
header('Content-Type: text/plain');
$zip = new ZipArchive;
if ($zip->open('AI_LH_FY26-27.docx') === TRUE) {
    for($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
        if (strpos($filename, 'word/header') === 0 || strpos($filename, 'word/footer') === 0 || $filename === 'word/document.xml') {
            echo "\n=== " . $filename . " ===\n";
            $content = $zip->getFromIndex($i);
            // Extract text from XML safely
            $text = preg_replace('/<[^>]+>/', ' ', $content);
            $text = preg_replace('/\s+/', ' ', $text);
            echo trim($text) . "\n";
            
            // Also print raw XML for debugging if needed
            echo "RAW: " . substr($content, 0, 500) . "...\n";
        }
    }
    $zip->close();
} else {
    echo 'Failed to open docx file';
}
?>
