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
    background: #fbdede !important;
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
/* Make Client Name column wider */


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

            
            <div class="container-fluid">

                <!-- Page Heading -->
                
                <!-- DataTales Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h5 class="text-center"><b>Client Details</b></h5>                  
                        <a class="btn btn-primary text-right" href="add_client.php" >Add New</a>
                    </div>

    <div class="card-body">
        <div class="table-responsive">

        <?php 
        $sql = "SELECT * FROM clients";
        $result = mysqli_query($conn,$sql);

        if ($result) {

            echo "<table class='table table-bordered' id='userTable' cellspacing='0'>";

            echo "
            <thead style='background:#dbe5ec; border:1px solid #ddd;'>
                <tr>
                    <th style='border:1px solid #ddd; width:40px; text-align:left;'>Sr. No</th>
                    <th style='border:1px solid #ddd; text-align:left;'>Client Name</th>
                    <th style='border:1px solid #ddd; text-align:left;'>Client City</th>
                    <th style='border:1px solid #ddd; text-align:left;'>Contact Name</th>
                    <th style='border:1px solid #ddd; text-align:left;'>Contact Mobile</th>
                    <th style='border:1px solid #ddd; text-align:left;'>Action</th>
                </tr>
            </thead>
            <tbody>";
            
            $srNo = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $client_name = $row['client_name'];
                $client_city = $row['client_city'];
                $contact_person = $row['contact_person'];
                $contact_mobile = $row['contact_mobile'];

                echo "<tr>
                        <td>$srNo</td>
                        <td>$client_name</td>
                        <td>$client_city</td>
                        <td>$contact_person</td>
                        <td>$contact_mobile</td>
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

                                <a href='delete_client.php?deleteid=$id' 
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
                <h5 class="modal-title">Client Details</h5>
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

    $('#userTable').DataTable({
    autoWidth: false,
    scrollX: false
});

    // Edit
    $(document).on("click", ".edit-employee", function () {
        let id = $(this).data("updateid");
        $.get("edit_client.php", { updateid: id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

    // View
    $(document).on("click", ".view-employee", function () {
        let id = $(this).data("id");
        $.get("view_clinet.php", { id:id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

});

</script>
