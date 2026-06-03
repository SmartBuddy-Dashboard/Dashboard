<?php
function run_git($cmd) {
    echo "Running: $cmd\n";
    $full_cmd = "cd /d c:\\xampp\\htdocs\\SmartBuddy28May && $cmd 2>&1";
    exec($full_cmd, $output, $return_var);
    echo "Return Code: $return_var\n";
    echo "Output:\n" . implode("\n", $output) . "\n\n";
}

echo "<pre>";
run_git("git init");
run_git("git add .");
run_git("git commit -m \"Initial commit of SmartBuddy Dashboard with UI improvements\"");
run_git("git remote add origin https://github.com/SmartBuddy-Dashboard/Dashboard.git");
run_git("git branch -M main");
run_git("git push -u origin main");
echo "</pre>";
?>
