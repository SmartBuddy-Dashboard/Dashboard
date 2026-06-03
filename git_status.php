<?php
$output = shell_exec('cd /d c:\xampp\htdocs\SmartBuddy28May && git status 2>&1');
echo "<pre>$output</pre>";
?>
