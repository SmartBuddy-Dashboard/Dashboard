<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];

$old_pdf_logic = "        const table = doc.content.find(c => c.table);
        if (table) {
            table.alignment = 'center';
            table.table.body.forEach((row, rowIndex) => {
                row.forEach(cell => {
                    cell.alignment = 'center';
                    cell.valign = 'middle';
                    cell.fontSize = rowIndex === 0 ? 9 : 8;
                    if (rowIndex === 0) cell.bold = true;
                });
            });
        }";

$new_pdf_logic = "        // Set High Contrast for all PDF text
        doc.defaultStyle.color = '#000000';
        if(doc.styles && doc.styles.tableHeader) {
            doc.styles.tableHeader.fillColor = '#10141f'; // Very dark background
            doc.styles.tableHeader.color = '#ffffff'; // White text
            doc.styles.tableHeader.bold = true;
        }

        const tableNode = doc.content.find(c => c.table);
        if (tableNode) {
            tableNode.alignment = 'center';
            
            // Solid black borders for high contrast
            tableNode.layout = {
                hLineWidth: function(i, node) { return 0.5; },
                vLineWidth: function(i, node) { return 0.5; },
                hLineColor: function(i, node) { return '#000000'; },
                vLineColor: function(i, node) { return '#000000'; },
                paddingLeft: function(i, node) { return 4; },
                paddingRight: function(i, node) { return 4; },
                paddingTop: function(i, node) { return 3; },
                paddingBottom: function(i, node) { return 3; }
            };

            tableNode.table.body.forEach((row, rowIndex) => {
                row.forEach(cell => {
                    cell.alignment = 'center';
                    cell.valign = 'middle';
                    cell.fontSize = rowIndex === 0 ? 10 : 9; // Slightly larger font
                    cell.color = rowIndex === 0 ? '#ffffff' : '#000000'; // Force black text for body
                    if (rowIndex === 0) cell.bold = true;
                });
            });
        }";

$files_modified = 0;

foreach ($folders as $folder) {
    $dir = $base_dir . '/' . $folder;
    if (!is_dir($dir)) continue;

    $files = scandir($dir);
    foreach ($files as $file) {
        if (strpos($file, 'report_') !== false && strpos($file, '.php') !== false) {
            $path = $dir . '/' . $file;
            $content = file_get_contents($path);

            if (strpos($content, "const table = doc.content.find(c => c.table);") !== false) {
                // Perform replacement
                $new_content = str_replace($old_pdf_logic, $new_pdf_logic, $content);
                
                // Some files might have slightly different spacing, so let's do a fallback replace if needed
                if ($content === $new_content) {
                    // Try regex replacement for the old logic block
                    $pattern = '/\s*const table = doc\.content\.find\(c => c\.table\);.*?if \(table\).*?table\.table\.body\.forEach.*?\}\);\s*\}/s';
                    $new_content = preg_replace($pattern, "\n" . $new_pdf_logic . "\n", $content);
                }

                if ($content !== $new_content) {
                    file_put_contents($path, $new_content);
                    echo "Updated PDF contrast in: $folder/$file\n";
                    $files_modified++;
                }
            }
        }
    }
}

echo "\nTotal files updated: $files_modified\n";
?>
