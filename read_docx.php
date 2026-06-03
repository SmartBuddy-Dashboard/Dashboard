<?php
$zip = new ZipArchive;
if ($zip->open('AI_LH_FY26-27.docx') === TRUE) {
    for($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
            if (strpos($filename, 'word/header1.xml') === 0 || strpos($filename, 'word/footer1.xml') === 0) {
                echo "<h3>" . htmlspecialchars($filename) . "</h3>";
                $content = $zip->getFromIndex($i);
                echo htmlspecialchars($content) . "<br><hr>";
            }
    }
    $zip->close();
} else {
    echo 'Failed to open docx file';
}
?>
