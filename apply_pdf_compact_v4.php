<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];

$new_pdf_logic = "        // COMPACT & High Contrast PDF
        doc.defaultStyle.color = '#000000';
        doc.defaultStyle.fontSize = 8; // Reduced font size to increase capacity per page
        
        if (!doc.styles) doc.styles = {};
        
        doc.styles.tableHeader = {
            fillColor: '#cccccc', 
            color: '#000000',     
            bold: true,
            fontSize: 9, // Slightly larger for header
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
            
            // THICK Solid black borders with REDUCED padding for compact rows
            tableNode.layout = {
                hLineWidth: function(i, node) { return 1; },
                vLineWidth: function(i, node) { return 1; },
                hLineColor: function(i, node) { return '#000000'; },
                vLineColor: function(i, node) { return '#000000'; },
                paddingLeft: function(i, node) { return 3; },
                paddingRight: function(i, node) { return 3; },
                paddingTop: function(i, node) { return 2; }, // Less padding = more rows per page
                paddingBottom: function(i, node) { return 2; }
            };

            tableNode.table.body.forEach((row, rowIndex) => {
                row.forEach(cell => {
                    cell.alignment = 'center';
                    cell.valign = 'middle';
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

            // Find the start of our previous v3 block
            $start_pos = strpos($content, "        // VERY High Contrast for PDF");
            
            if ($start_pos !== false) {
                // Find where the block ends
                $end_search = "cell.color = '#000000';\r\n                });\r\n            });\r\n        }";
                $end_search2 = "cell.color = '#000000';\n                });\n            });\n        }";
                
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
                        echo "Updated PDF compactness in: $folder/$file\n";
                        $files_modified++;
                    }
                } else {
                    echo "Could not find end block in: $folder/$file\n";
                }
            } else {
                echo "Could not find start block in: $folder/$file\n";
            }
        }
    }
}

echo "\nTotal files updated: $files_modified\n";
?>
