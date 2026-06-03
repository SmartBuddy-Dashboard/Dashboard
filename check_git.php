<?php
$output = shell_exec('git --version 2>&1');
echo "Git version: $output\n";
?>
