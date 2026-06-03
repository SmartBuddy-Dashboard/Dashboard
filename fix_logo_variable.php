<?php
$folders = ['Admin', 'operation', 'client'];
$modified = 0;

foreach ($folders as $folder) {
    $files = glob(__DIR__ . '/' . $folder . '/*.php');
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        // Some files use COMPANY_LOGO_BASE64 instead of logoBase64.
        // Let's replace the strict 'image: logoBase64,' with a fallback check.
        $old_logo_code = "image: logoBase64,";
        $new_logo_code = "image: (typeof logoBase64 !== 'undefined' ? logoBase64 : (typeof COMPANY_LOGO_BASE64 !== 'undefined' ? COMPANY_LOGO_BASE64 : '')),";
        
        if (strpos($content, $old_logo_code) !== false) {
            $content = str_replace($old_logo_code, $new_logo_code, $content);
            file_put_contents($file, $content);
            echo "Fixed logo variable in: $file\n<br>";
            $modified++;
        }
        
        // Print logo path fallback
        $old_print_logo = '<img src="${logoPath}" style="height:50px;">';
        $new_print_logo = '<img src="${typeof logoPath !== \'undefined\' ? logoPath : (typeof companyLogoPath !== \'undefined\' ? companyLogoPath : \'\')}" style="height:50px;">';
        
        if (strpos($content, $old_print_logo) !== false) {
            $content = file_get_contents($file); // Reload to ensure we write both
            $content = str_replace($old_print_logo, $new_print_logo, $content);
            file_put_contents($file, $content);
            echo "Fixed print logo variable in: $file\n<br>";
        }
    }
}
echo "Done. Fixed $modified files.";
?>
