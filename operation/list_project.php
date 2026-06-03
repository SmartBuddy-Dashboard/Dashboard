<?php 
require_once('include/header.php');
require_once('include/config.php');




require_once('include/navbar.php');
?>
<head>
<style>
/* =========================================
   GENERAL TABLE STYLING
========================================= */

.modal-dialog {
    max-width: 50% !important;
    margin: 1.75rem auto;
}

.modal-content {
    border-radius: 10px;
}

#userTable {
    border: 2px solid #ddd !important;
}

#userTable th,
#userTable td {
    border: 1px solid #ddd !important;
}

#userTable tbody tr:nth-child(odd) {
    
}

/* Small search box */
.dataTables_filter input {
    width: 150px;
    height: 28px;
    font-size: 13px;
    padding: 4px 8px;
}

/* Action column width (Desktop default) */
#userTable th:last-child,
#userTable td:last-child {
    width: 110px;
    text-align: center;
    white-space: nowrap;
}

/* =========================================
   MOBILE ONLY (≤768px)
========================================= */

@media screen and (max-width: 768px) {

    .modal-dialog {
        max-width: 95% !important;   /* Full width on mobile */
        margin: 10px auto;
    }

    .modal-content {
        border-radius: 8px;
    }

    .modal-body {
        padding: 15px;
    }

    .modal-header h5 {
        font-size: 16px;
    }

    .modal-footer .btn {
        font-size: 14px;
        padding: 6px 12px;
    }

    /* Enable horizontal scroll */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    /* Force table wider than screen */
    #userTable {
        min-width: 800px;   /* Forces horizontal scroll */
        width: 100%;
        table-layout: auto;
    }

    /* Prevent text wrapping */
    #userTable th,
    #userTable td {
        white-space: nowrap;
    }

    /* Allow wrapping for long text columns */
    #userTable td:nth-child(2),
    #userTable td:nth-child(4) {
        white-space: normal;
        min-width: 200px;
        word-break: break-word;
    }

    /* Action buttons touch-friendly */
    #userTable td:last-child {
        min-width: 160px;
    }

    #userTable td:last-child .btn {
        min-width: 38px;
        min-height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Custom Scrollbar */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
}

/* =========================================
   DESKTOP ONLY (≥769px)
========================================= */

@media screen and (min-width: 769px) {

    /* Remove horizontal scroll completely */
    .table-responsive {
    overflow-x: visible;
}

    #userTable {
        min-width: 100%;
        table-layout: fixed;
    }

    #userTable th,
    #userTable td {
        white-space: normal;
    }
}

</style>

</head>
 <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h5 class="text-center"><b>Project Details</b></h5>                  
                        <a class="btn btn-primary text-right" href="add_project.php" >Add New</a>
                    </div>

    <div class="card-body">
        <div class="table-responsive">

        <?php 
        $sql = "SELECT * FROM projects";
        $result = mysqli_query($conn,$sql);

        if ($result) {

            echo "<table class='table table-bordered' id='userTable' cellspacing='0' style='border:2px solid #ddd;'>";
            echo "
            <thead style=' border:1px solid #ddd;'>
                <tr>
                    <th style='border:1px solid #ddd;'>Sr. No</th>
                    <th style='border:1px solid #ddd;'>Project Name </th>
                    <th style='border:1px solid #ddd;'>SO Number </th>
                    <th style='border:1px solid #ddd;'>Client Name </th>
                    <th style='border:1px solid #ddd;'>Project Starts</th>
                    <th style='border:1px solid #ddd;'>Project Status</th>
                    
                    <th style='border:1px solid #ddd;'>Action</th>
                </tr>
            </thead>
            <tbody>";
            
            $srNo = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
              $project_name = $row['project_name'];
              $client_name = $row['client_name'];
               $sale_ord_no = $row['sale_ord_no'];
               $project_starts   = $row['project_starts'];
                $project_status   = $row['project_status'];
                   // DATE FORMAT (DMY)
    $start_date = !empty($project_starts) ? date("d-m-Y", strtotime($project_starts)) : '-';
    $end_date   = !empty($project_end) ? date("d-m-Y", strtotime($project_end)) : '-';

                echo "<tr>
                        <td>$srNo</td>
                        <td>$project_name</td>
                        <td>$sale_ord_no</td>
                        <td>$client_name</td>
                        
                         <td>$start_date</td>
            <td>$project_status</td>
                        
                        <td>
                            <div style='display:flex; gap:8px;'>

                                <a href='#' class='btn btn-sm btn-info edit-employee' 
                                    data-toggle='modal' data-target='#employeeModal' 
                                    data-updateid='$id' title='Update'>
                                    <i class='fas fa-edit'></i>
                                </a>

                                <a href='#' class='btn btn-sm btn-primary view-employee'
                                    data-toggle='modal' data-target='#employeeModal'
                                    data-id='$id' title='View'>
                                    <i class='fas fa-eye'></i>
                                </a>

                                <a href='delete_project.php?deleteid=$id' 
                                    class='btn btn-sm btn-danger' title='Delete'
                                    onclick=\"return confirm('Are you sure, you want to delete?')\">
                                    <i class='fas fa-trash'></i>
                                </a>

                            </div>
                        </td>
                    </tr>";
                $srNo++;
            }
            echo "</tbody></table>";
            mysqli_free_result($result);
        }
        mysqli_close($conn);
        ?>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Projects Details</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <div class="modal-body">
                <div id="employeeDetails"></div>
            </div>

        </div>
    </div>
</div>

</div>
</div>
</div>


<?php include('include/scripts.php');?>
<?php include('include/footer.php');?>



<!-- DataTable -->
<script src="js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {

    $('#userTable').DataTable();

    // Edit
    $(document).on("click", ".edit-employee", function () {
        let id = $(this).data("updateid");
        $.get("edit_project.php", { updateid: id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

    // View
    $(document).on("click", ".view-employee", function () {
        let id = $(this).data("id");
        $.get("view_project.php", { id:id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

});

</script>
