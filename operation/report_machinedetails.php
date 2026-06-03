<?php 
require_once('include/header.php');
require_once('include/config.php');




require_once('include/navbar.php');
?>
<head>
    <!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<style>
/* =========================================
   MODAL (DESKTOP DEFAULT)
========================================= */

.modal-dialog {
    max-width: 50%;
    margin: 1.75rem auto;
}

.modal-content {
    border-radius: 10px;
}


/* =========================================
   MOBILE MODAL (ONLY ≤768px)
========================================= */

@media (max-width: 768px) {

  #employeeModal .modal-dialog {
    max-width: 95% !important;
    margin: 10px auto;
  }

  #employeeModal .modal-content {
    height: 90vh;
  }

  #employeeModal .modal-body {
    max-height: calc(90vh - 120px);
    overflow-y: auto;
    padding: 12px;
  }

  #employeeModal .modal-title {
    font-size: 18px;
  }
}


/* =========================================
   TABLE STYLING
========================================= */

#userTable {
    border: 2px solid #ddd !important;
}

#userTable th,
#userTable td {
    border: 1px solid #ddd !important;
}

#userTable tbody tr:nth-child(odd) {
    
}

/* Smaller search box */
.dataTables_filter input {
    width: 150px;
    height: 28px;
    font-size: 13px;
    padding: 4px 8px;
}




/* =========================================
   MOBILE ONLY – ENABLE HORIZONTAL SCROLL
========================================= */

@media screen and (max-width: 768px) {

    .table-responsive {
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }

    .dataTables_wrapper {
        overflow-x: auto !important;
    }

    /* Force table wider than screen */
    #userTable {
        min-width: 800px;   /* forces scrollbar */
        width: 100%;
        table-layout: auto;
    }

    #userTable th,
    #userTable td {
        white-space: nowrap;
    }

    /* Scrollbar style */
    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
}


/* =========================================
   DESKTOP ONLY – NO HORIZONTAL SCROLL
========================================= */

@media screen and (min-width: 769px) {



       .table-responsive,
.dataTables_wrapper {
    overflow-x: visible !important;
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
                        <h5 class="text-center"><b>Machine details</b></h5>                  
                     
                    </div>

    <div class="card-body">
        <div class="table-responsive">

        <?php 
        $sql = "SELECT * FROM machines";
        $result = mysqli_query($conn,$sql);

        if ($result) {

            echo "<table class='table table-bordered' id='userTable' cellspacing='0' style='border:2px solid #ddd;'>";
            echo "
            <thead style=' border:1px solid #ddd;'>
                <tr>
                    <th style='border:1px solid #ddd;'>Sr. No</th>
                    <th style='border:1px solid #ddd;'>Machine ID</th>
                    <th style='border:1px solid #ddd;'>Amount</th>
                   <th style='border:1px solid #ddd;'>Status</th>
                    <th style='border:1px solid #ddd;'>Action</th>
                </tr>
            </thead>
            <tbody>";
            
            $srNo = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $machine_id = $row['machine_id'];
              $uses_amt   = $row['uses_amt'];
              $status   = $row['status'];

                echo "<tr>
                        <td>$srNo</td>
                        <td>$machine_id</td>
                        <td>$uses_amt</td>
                        <td>$status</td>
                        
                        <td>
                            <div style='display:flex; gap:8px;'>

                              <a href='#' class='btn btn-sm btn-primary view-employee'
                                    data-toggle='modal' data-target='#employeeModal'
                                    data-id='$machine_id' title='View'>
                                    <i class='fas fa-eye'></i>
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
                <h5 class="modal-title">Machine Details</h5>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Popper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- DataTable -->
<script src="js/jquery.dataTables.min.js"></script>



<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>


<script>
    function getTimestamp() {
    const now = new Date();

    const options = {
        timeZone: "Asia/Kolkata",
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false
    };

    const parts = new Intl.DateTimeFormat("en-GB", options).formatToParts(now);

    const get = type => parts.find(p => p.type === type).value;

    return (
        get("year") +
        get("month") +
        get("day") + "_" +
        get("hour") +
        get("minute") +
        get("second")
    );
}


$(document).ready(function() {

$('#userTable').DataTable({
    dom: "<'row'<'col-md-12'>>" +
         "<'row'<'col-md-6'l><'col-md-6 text-right'f>>" +
         "<'row'<'col-md-12'tr>>" +
         "<'row'<'col-md-5'i><'col-md-7 text-right'p>>",

    lengthMenu: [10, 25, 50, 100]
});


    // View employee modal
    $(document).on("click", ".view-employee", function () {
        let id = $(this).data("id");
        $.get("view_machinedet.php", { id: id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

});
</script>

