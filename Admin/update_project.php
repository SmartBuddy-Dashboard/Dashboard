<?php
include('include/config.php');

if (isset($_POST['update'])) {

    // 🔑 Project ID (must come from hidden input OR URL)
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $client_name    = mysqli_real_escape_string($conn, $_POST['client_name']);
    $project_name   = mysqli_real_escape_string($conn, $_POST['project_name']);
    $work_ord_no    = mysqli_real_escape_string($conn, $_POST['work_ord_no']);
    $sale_ord_no    = mysqli_real_escape_string($conn, $_POST['sale_ord_no']);
    $project_starts = mysqli_real_escape_string($conn, $_POST['project_starts']);
    $project_end    = !empty($_POST['project_end'])
                        ? mysqli_real_escape_string($conn, $_POST['project_end'])
                        : NULL;
    $project_status = mysqli_real_escape_string($conn, $_POST['project_status']);
    $remark         = mysqli_real_escape_string($conn, $_POST['remark']);

    /* ✅ Server-side validation */
    if ($project_status === 'Completed' && empty($project_end)) {
        echo "<script>alert('Project End date is required for Completed status');</script>";
        exit;
    }

    if ($project_end && $project_end < $project_starts) {
        echo "<script>alert('Project End date cannot be before Start date');</script>";
        exit;
    }

    /* ✅ Update Query */
    $update = "
        UPDATE projects SET
            client_name    = '$client_name',
            project_name   = '$project_name',
            work_ord_no    = '$work_ord_no',
            sale_ord_no    = '$sale_ord_no',
            project_starts = '$project_starts',
            project_end    = " . ($project_end ? "'$project_end'" : "NULL") . ",
            project_status = '$project_status',
            remark         = '$remark'
        WHERE id = '$id'
    ";

    if (mysqli_query($conn, $update)) {
        echo "<script>
                alert('Project updated successfully!');
                window.location.href = 'list_project.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating project: " . mysqli_error($conn) . "');
                window.history.back();
              </script>";
    }

} else {
    header("Location: list_project.php");
    exit();
}
?>
