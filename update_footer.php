<?php
$folders = ['Admin', 'operation', 'client'];
$modified = 0;

$old_pdf_footer = "text: 'Copyright © Aarya Group of Industries Nashik, Maharashtra, India.',";
$new_pdf_footer = "text: 'AARYA INNOVTECH PVT. LTD. CIN : U29305MH2019PTC327551\\nNashik Office : Flat No.4A, Sayali Darshan -A-Wing. Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003.\\nMumbai Office : Flat No.C-03, The Maharashtra Chs Ltd. C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012.\\nFactory : S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010.\\n+91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com',";

$old_print_footer = "<div>Copyright © Aarya Group of Industries Nashik, Maharashtra, India.</div>";
$new_print_footer = "<div><strong>AARYA INNOVTECH PVT. LTD.</strong> CIN : U29305MH2019PTC327551<br><strong>Nashik Office :</strong> Flat No.4A, Sayali Darshan -A-Wing, Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003.<br><strong>Mumbai Office :</strong> Flat No.C-03, The Maharashtra Chs Ltd, C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012.<br><strong>Factory :</strong> S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010.<br>+91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com</div>";

// We also need to change print CSS margin and height for footer since it's much bigger now.
$old_print_css = <<<EOD
            @media print {
                @page { margin-bottom: 35mm; }
                .dt-print-footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 22mm;
                    border-top: 1px solid #000;
                    font-size: 11px;
                    font-weight: bold;
                    line-height: 18px;
                    padding: 10px 40px;
                    background: #fff;
                    display: flex;
                    justify-content: center;
                }
            }
EOD;

$new_print_css = <<<EOD
            @media print {
                @page { margin-bottom: 45mm; }
                .dt-print-footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: auto;
                    border-top: 1px solid #000;
                    font-size: 10px;
                    line-height: 14px;
                    padding: 5px 10px;
                    background: #fff;
                    text-align: center;
                }
            }
EOD;

foreach ($folders as $folder) {
    $files = glob(__DIR__ . '/' . $folder . '/*.php');
    foreach ($files as $file) {
        $content = file_get_contents($file);
        
        if (strpos($content, $old_pdf_footer) !== false) {
            $content = str_replace($old_pdf_footer, $new_pdf_footer, $content);
            $content = str_replace($old_print_footer, $new_print_footer, $content);
            $content = str_replace($old_print_css, $new_print_css, $content);
            
            // For Excel footer as well, which is in the same file
            $old_excel_footer = "<t>Copyright © Aarya Group of Industries Nashik, Maharashtra, India.</t>";
            $new_excel_footer = "<t>AARYA INNOVTECH PVT. LTD. CIN : U29305MH2019PTC327551 - Nashik Office : Flat No.4A, Sayali Darshan -A-Wing. Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003. - Mumbai Office : Flat No.C-03, The Maharashtra Chs Ltd. C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012. - Factory : S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010. - +91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com</t>";
            $content = str_replace($old_excel_footer, $new_excel_footer, $content);
            
            file_put_contents($file, $content);
            echo "Updated footer in: $file\n<br>";
            $modified++;
        }
    }
}
echo "Done. Fixed $modified files.";
?>
