<?php
include_once('include/config.php');

$id = $_GET['id']; // Get the ID from the URL parameter

$query = "SELECT * FROM projects WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $project_name   = $row['project_name'];
    
    $project_starts = $row['project_starts'];
    $project_end    = $row['project_end'];
     $remark    = $row['remark'];
    ?>

    <style>
        /* Bulletproof contrast fix for AJAX modal content */
        .ajax-modal-table {
            color: #ffffff !important; 
            border-color: #4a5568 !important;
        }
        .ajax-modal-table th, .ajax-modal-table td {
            color: #ffffff !important;
            border-color: #4a5568 !important;
            background-color: transparent !important;
        }
        [data-theme="light"] .ajax-modal-table {
            color: #333 !important;
        }
        [data-theme="light"] .ajax-modal-table th, [data-theme="light"] .ajax-modal-table td {
            color: #333 !important;
            border-color: #ddd !important;
        }
    </style>

    <table class="table table-bordered ajax-modal-table" border="1" cellpadding="10" cellspacing="0" 
           style="width: 100%; margin: auto; text-align:left;">
        <tbody>
            <tr>
                <th style="width: 35%;">Project Name</th>
                <td><?php echo htmlspecialchars($project_name ?? ''); ?></td>
            </tr>
            
            <tr>
                <th>Project Starts</th>
                <td><?php echo !empty($project_starts) ? date("d-m-Y", strtotime($project_starts)) : '-'; ?></td>
            </tr>
            <tr>
                <th>Project End</th>
                <td><?php echo !empty($project_end) ? date("d-m-Y", strtotime($project_end)) : '-'; ?></td>
            </tr>
            <tr>
                <th>Remark</th>
                <td><?php echo htmlspecialchars($remark ?? ''); ?></td>
            </tr>
        </tbody>
    </table>

<?php
} else {
    echo "<h3 style='color: #ff6b6b; text-align: center; margin-top:20px;'>Error retrieving project details.</h3>";
}
?>
