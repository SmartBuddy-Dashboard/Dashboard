<?php
$base_dir = __DIR__;
$folders = ['Admin', 'client', 'operation'];

$css_append = "

/* =========================================================
   NEW DARK MODE CONTRAST FIXES (Sidebar, Tables, Pagination, Modals)
========================================================= */

/* Sidebar Sub-menus */
html[data-theme=\"dark\"] .collapse-inner.bg-white {
    background-color: var(--bg-card) !important;
}
html[data-theme=\"dark\"] .collapse-item {
    color: var(--text-main) !important;
}
html[data-theme=\"dark\"] .collapse-item:hover {
    background-color: var(--bg-main) !important;
    color: var(--primary-color) !important;
}
html[data-theme=\"dark\"] .collapse-header {
    color: var(--text-muted) !important;
}

/* DataTables Text & Pagination */
html[data-theme=\"dark\"] .dataTables_info,
html[data-theme=\"dark\"] .dataTables_length,
html[data-theme=\"dark\"] .dataTables_length label,
html[data-theme=\"dark\"] .dataTables_filter,
html[data-theme=\"dark\"] .dataTables_filter label {
    color: var(--text-main) !important;
}
html[data-theme=\"dark\"] .page-link {
    background-color: var(--bg-card) !important;
    border-color: var(--border-color) !important;
    color: var(--text-main) !important;
}
html[data-theme=\"dark\"] .page-item.disabled .page-link {
    background-color: var(--bg-main) !important;
    color: var(--text-muted) !important;
    border-color: var(--border-color) !important;
}
html[data-theme=\"dark\"] .page-item.active .page-link {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: #fff !important;
}

/* All Tables inside Dark Theme (including Modals) */
html[data-theme=\"dark\"] .table {
    color: var(--text-main) !important;
}
html[data-theme=\"dark\"] .table th,
html[data-theme=\"dark\"] .table td {
    color: var(--text-main) !important;
    border-color: var(--border-color) !important;
}
html[data-theme=\"dark\"] table.dataTable tbody tr {
    background-color: var(--bg-card) !important;
}

/* Modals */
html[data-theme=\"dark\"] .modal-content {
    background-color: var(--bg-card) !important;
    color: var(--text-main) !important;
    border: 1px solid var(--border-color) !important;
}
html[data-theme=\"dark\"] .modal-header,
html[data-theme=\"dark\"] .modal-footer {
    border-color: var(--border-color) !important;
}
html[data-theme=\"dark\"] .modal-title {
    color: var(--text-main) !important;
}
html[data-theme=\"dark\"] .close {
    color: var(--text-main) !important;
    text-shadow: none;
    opacity: 0.8;
}
html[data-theme=\"dark\"] .close:hover {
    color: #fff !important;
    opacity: 1;
}
";

$files_modified = 0;

foreach ($folders as $folder) {
    $path = $base_dir . '/' . $folder . '/css/theme.css';
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (strpos($content, "NEW DARK MODE CONTRAST FIXES") === false) {
            file_put_contents($path, $content . $css_append);
            echo "Updated $path\n";
            $files_modified++;
        } else {
            echo "Already updated: $path\n";
        }
    }
}

// Also update premium-industrial.css just in case
$premium_path = $base_dir . '/assets/css/premium-industrial.css';
if (file_exists($premium_path)) {
    $content = file_get_contents($premium_path);
    if (strpos($content, "NEW DARK MODE CONTRAST FIXES") === false) {
        file_put_contents($premium_path, $content . $css_append);
        echo "Updated $premium_path\n";
        $files_modified++;
    } else {
        echo "Already updated: $premium_path\n";
    }
}

echo "\nTotal CSS files updated: $files_modified\n";
?>
