<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];

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

            $start_pos = strpos($content, "        // Set High Contrast for all PDF text");
            
            if ($start_pos !== false) {
                // Find where the customize block ends. In our previous script we ended with `}); }); }`
                // Let's just find the closing brace of the if(tableNode) block
                $end_search = "if (rowIndex === 0) cell.bold = true;\r\n                });\r\n            });\r\n        }";
                $end_search2 = "if (rowIndex === 0) cell.bold = true;\n                });\n            });\n        }";
                
                $end_pos = strpos($content, $end_search, $start_pos);
                if($end_pos === false) {
                    $end_pos = strpos($content, $end_search2, $start_pos);
                    $end_length = strlen($end_search2);
                } else {
                    $end_length = strlen($end_search);
                }

                if ($end_pos !== false) {
                    $old_length = ($end_pos + $end_length) - $start_pos;
                    $new_content = substr_replace($content, $new_pdf_logic, $start_pos, $old_length);
                    
                    if ($content !== $new_content) {
                        file_put_contents($path, $new_content);
                        echo "Updated PDF contrast in: $folder/$file\n";
                        $files_modified++;
                    }
                } else {
                    echo "Could not find end block in: $folder/$file\n";
                }
            }
        }
    }
}

echo "\nTotal files updated: $files_modified\n";
?>
