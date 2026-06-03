<?php 
require_once('include/header.php');
require_once('include/config.php');


require_once('include/navbar.php');
?>
<head>
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
                        <h5 class="text-center"><b> Update Machine ID</b></h5>                  
                        
                    </div>

    <div class="card-body">
        <div class="table-responsive">

        <?php 
        $sql = "SELECT * FROM machines 
          ";
        $result = mysqli_query($conn,$sql);

        if ($result) {

            echo "<table class='table table-bordered' id='userTable' cellspacing='0' style='border:2px solid #ddd;'>";
            echo "
            <thead style=' border:1px solid #ddd;'>
                <tr>
                    <th style='border:1px solid #ddd;'>Sr. No</th>
                    <th style='border:1px solid #ddd;'>Machine ID</th>
                    <th style='border:1px solid #ddd;'>Entery Fee</th>
                    
                   
                    <th style='border:1px solid #ddd;'>Action</th>
                </tr>
            </thead>
            <tbody>";
            
            $srNo = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $machine_id = $row['machine_id'];
              $uses_amt   = $row['uses_amt'];

               

                echo "<tr>
                        <td>$srNo</td>
                        <td>$machine_id</td>
                        <td>$uses_amt</td>
                        

                        
                        <td>
                            <div style='display:flex; gap:8px;'>

                                <a href='#' class='btn btn-sm btn-info edit-employee' 
                                    data-toggle='modal' data-target='#employeeModal' 
                                    data-updateid='$id' title='Update'>
                                    <i class='fas fa-edit'></i>
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
                <h5 class="modal-title">Machines Details</h5>
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
        $.get("edit_newmachine.php", { updateid: id }, function(data){
            $("#employeeDetails").html(data);
        });
    });


});

</script>
