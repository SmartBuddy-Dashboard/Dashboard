<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];

$old_pdf_logic = "        // Set High Contrast for all PDF text
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

$new_pdf_logic = "        // VERY High Contrast for PDF
        doc.defaultStyle.color = '#000000';
        doc.defaultStyle.fontSize = 10;
        
        if (!doc.styles) doc.styles = {};
        
        doc.styles.tableHeader = {
            fillColor: '#cccccc', // Light gray background
            color: '#000000',     // Pure black text
            bold: true,
            fontSize: 11,
            alignment: 'center'
        };
        doc.styles.tableBodyEven = {
            alignment: 'center',
            color: '#000000'
        };
        doc.styles.tableBodyOdd = {
            alignment: 'center',
            color: '#000000'
        };

        const tableNode = doc.content.find(c => c.table);
        if (tableNode) {
            tableNode.alignment = 'center';
            
            // THICK Solid black borders for maximum contrast
            tableNode.layout = {
                hLineWidth: function(i, node) { return 1; },
                vLineWidth: function(i, node) { return 1; },
                hLineColor: function(i, node) { return '#000000'; },
                vLineColor: function(i, node) { return '#000000'; },
                paddingLeft: function(i, node) { return 5; },
                paddingRight: function(i, node) { return 5; },
                paddingTop: function(i, node) { return 4; },
                paddingBottom: function(i, node) { return 4; }
            };

            tableNode.table.body.forEach((row, rowIndex) => {
                row.forEach(cell => {
                    cell.alignment = 'center';
                    cell.valign = 'middle';
                    // Force text color black everywhere
                    cell.color = '#000000';
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

            if (strpos($content, "// Set High Contrast for all PDF text") !== false) {
                // Try regex replacement for the old logic block
                $pattern = '/\s*\/\/ Set High Contrast for all PDF text.*?if \(rowIndex === 0\) cell\.bold = true;\s*\}\);\s*\}\);\s*\}/s';
                $new_content = preg_replace($pattern, "\n" . $new_pdf_logic, $content);

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
