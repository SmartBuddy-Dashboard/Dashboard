<?php 
require_once('include/header.php');
require_once('include/config.php');




require_once('include/navbar.php');


// ✅ Check session variables properly
if (!isset($_SESSION['mobile']) || !isset($_SESSION['client_name'])) {
    echo "<script>alert('User not logged in.');</script>";
    header("Location: index.php"); // Redirect to login page if not set
    exit();
}



$mobile = $_SESSION['mobile'];
$client_name = $_SESSION['client_name'];

?>
<head>
    <!-- DataTables Buttons CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<style>
.modal-dialog {
    max-width: 50% !important;
    margin: 1.75rem auto;
}
.modal-content {
    border-radius: 10px;
}
/* ================= MOBILE MODAL BIG ================= */
@media (max-width: 768px) {

  #employeeModal .modal-dialog {
    max-width: 95% !important;   /* wider */
    margin: 10px auto;
  }

  #employeeModal .modal-content {
    height: 90vh;                /* taller */
  }

  #employeeModal .modal-body {
    max-height: calc(90vh - 120px);
    overflow-y: auto;            /* scroll inside */
    padding: 12px;
  }

  #employeeModal .modal-title {
    font-size: 18px;             /* readable */
  }
}

/* Always apply styles to table */
#userTable {
    border: 2px solid #ddd !important;
}

#userTable th, #userTable td {
    border: 1px solid #ddd !important;
}

/* Keep the odd-row background */
#userTable tbody tr:nth-child(odd) {
   /* removed for dark mode compatibility */
}
.dt-btn-custom {
    padding: 8px 15px;
    border-radius: 8px;
    background: #fff !important;
    border: 2px solid #000 !important;
    color: #000 !important;
    font-size: 13px;
    font-weight: 600;
    margin-right: 6px;
    transition: .3s;
}

.dt-btn-custom:hover {
    border-color: #00b894 !important;
    color: #00b894 !important;
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
        $sql = "SELECT * FROM machines 
        WHERE client_name = '$client_name'
        AND client_name IS NOT NULL
        AND TRIM(client_name) <> ''";
        $result = mysqli_query($conn,$sql);

        if ($result) {

            echo "<table class='table table-bordered' id='userTable' cellspacing='0' style='border:2px solid #ddd;'>";
            echo "
            <thead style='border:1px solid #ddd;'>
                <tr>
                    <th style='border:1px solid #ddd; width:40px;'>Sr. No</th>
                    <th style='border:1px solid #ddd;'>Client Name</th>
                    <th style='border:1px solid #ddd;'>Project Name</th>
                    <th style='border:1px solid #ddd;'>Machine ID</th>
                    <th style='border:1px solid #ddd;'>City</th>
                    <th style='border:1px solid #ddd;'>Amount</th>
                    <th style='border:1px solid #ddd;'>Mode</th>
                   
                    <th style='border:1px solid #ddd;'>Action</th>
                </tr>
            </thead>
            <tbody>";
            
            $srNo = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $machine_id = $row['machine_id'];
              $client_name   = $row['client_name'];
              $project_name   = $row['project_name'];
              $city   = $row['city'];
              $uses_amt   = $row['uses_amt'];

                 /* -------- MODE LOGIC -------- */
        $modes = [];

        if ($row['free'] === 'Yes') {
            $modes[] = 'BUTTON';
        }
        if ($row['coin'] === 'Yes') {
            $modes[] = 'COIN';
        }
        if ($row['upi'] === 'Yes') {
            $modes[] = 'UPI';
        }
        if ($row['smart_card'] === 'Yes') {
            $modes[] = 'SMART CARD';
        }
        if ($row['digital_token'] === 'Yes') {
            $modes[] = 'DIGITAL TOKEN';
        }

        $modeText = implode(', ', $modes);
        /* ---------------------------- */


                echo "<tr>
                        <td>$srNo</td>
                        <td>$client_name</td>
                        <td>$project_name</td>
                        <td>$machine_id</td>
                        <td>$city</td>
                        <td>$uses_amt</td>

                        <td>$modeText</td>

                        
                        <td>
    <div style='display:flex; gap:8px;'>
       

        <a href='#' class='btn btn-sm btn-primary view-employee'
           data-toggle='modal' data-target='#employeeModal'
           data-id='$id' title='View'>
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

const now = new Date();
const timestamp = now.getFullYear() + String(now.getMonth() + 1).padStart(2, '0') + String(now.getDate()).padStart(2, '0') + '_' + String(now.getHours()).padStart(2, '0') + String(now.getMinutes()).padStart(2, '0');
const fileName = `Report-`;

$(document).ready(function() {

    $('#userTable').DataTable({
        dom: "<'row'<'col-md-12'B>>" +
             "<'row'<'col-md-6'l><'col-md-6 text-right'f>>" +
             "<'row'<'col-md-12'tr>>" +
             "<'row'<'col-md-5'i><'col-md-7 text-right'p>>",
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'dt-btn-custom',
                exportOptions: { columns: ':not(:last-child)' }
            },
            {
    extend: 'pdfHtml5',
    filename: fileName,
    orientation: 'landscape',
    pageSize: 'A4',
    title: '',
    className: 'dt-btn-custom',
    exportOptions: { columns: ':visible' },
    customize: function (doc) {
        let imgBase64 = typeof logoBase64 !== 'undefined' ? logoBase64 : (typeof COMPANY_LOGO_BASE64 !== 'undefined' ? COMPANY_LOGO_BASE64 : '');
        let titleText = `SMART TOILET`;
        
        let headerCols = [
            {
                width: '30%',
                text: [
                    { text: 'Generated By: ', bold: true },
                    { text: '<?php echo isset($_SESSION["mobile"]) ? $_SESSION["mobile"] : (isset($_SESSION["client_mobile"]) ? $_SESSION["client_mobile"] : "User"); ?>\n' },
                    { text: 'Date: ', bold: true },
                    { text: typeof now !== 'undefined' ? now.toLocaleString('en-IN') : new Date().toLocaleString('en-IN') }
                ],
                fontSize: 9,
                alignment: 'left'
            },
            {
                width: imgBase64 ? '40%' : '70%',
                stack: [
                    { text: 'SMART TOILET', alignment: 'center', fontSize: 16, bold: true },
                    { text: titleText, alignment: 'center', fontSize: 12, bold: true }
                ]
            }
        ];
        
        if (imgBase64) {
            headerCols.push({
                width: '30%',
                image: imgBase64,
                fit: [60, 60],
                alignment: 'right'
            });
        }
        
        doc.content.unshift({
            columns: headerCols,
            margin: [0, 0, 0, 10]
        });

        const table = doc.content.find(c => c.table);
        if (table) {
            table.alignment = 'center';
            table.table.body.forEach((row, rowIndex) => {
                row.forEach(cell => {
                    cell.alignment = 'center';
                    cell.valign = 'middle';
                    cell.fontSize = rowIndex === 0 ? 9 : 8;
                    if (rowIndex === 0) cell.bold = true;
                });
            });
        }

        doc.footer = function (currentPage, pageCount) {
            return {
                columns: [
                    { width: '*', text: '', alignment: 'left' },
                    {
                        width: 'auto',
                        text: 'AARYA INNOVTECH PVT. LTD. CIN : U29305MH2019PTC327551\nNashik Office : Flat No.4A, Sayali Darshan -A-Wing. Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003.\nMumbai Office : Flat No.C-03, The Maharashtra Chs Ltd. C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012.\nFactory : S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010.\n+91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com',
                        alignment: 'center',
                        fontSize: 8
                    },
                    {
                        width: '*',
                        text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                        alignment: 'right',
                        fontSize: 8,
                        margin: [0, 0, 20, 0]
                    }
                ],
                margin: [20, 0, 20, 10]
            };
        };
    }
}}
                    ],
                    fontSize: 9,
                    alignment: 'left'
                },
                {
                    width: '40%',
                    stack: [
                        { text: 'SMART TOILET', alignment: 'center', fontSize: 16, bold: true },
                        { text: 'Report', alignment: 'center', fontSize: 12, bold: true }
                    ]
                },
                {
                    width: '30%',
                    image: (typeof logoBase64 !== 'undefined' ? logoBase64 : (typeof COMPANY_LOGO_BASE64 !== 'undefined' ? COMPANY_LOGO_BASE64 : '')),
                    fit: [60, 60],
                    alignment: 'right'
                }
            ],
            margin: [0, 0, 0, 10]
        });

        const table = doc.content.find(c => c.table);
        if (table) {
            table.alignment = 'center';
            table.table.body.forEach((row, rowIndex) => {
                row.forEach(cell => {
                    cell.alignment = 'center';
                    cell.valign = 'middle';
                    cell.fontSize = rowIndex === 0 ? 9 : 8;
                    if (rowIndex === 0) cell.bold = true;
                });
            });
        }

        doc.footer = function (currentPage, pageCount) {
            return {
                columns: [
                    { width: '*', text: '', alignment: 'left' },
                    {
                        width: 'auto',
                        text: 'AARYA INNOVTECH PVT. LTD. CIN : U29305MH2019PTC327551\nNashik Office : Flat No.4A, Sayali Darshan -A-Wing. Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003.\nMumbai Office : Flat No.C-03, The Maharashtra Chs Ltd. C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012.\nFactory : S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010.\n+91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com',
                        alignment: 'center',
                        fontSize: 8
                    },
                    {
                        width: '*',
                        text: 'Page ' + currentPage.toString() + ' of ' + pageCount,
                        alignment: 'right',
                        fontSize: 8,
                        margin: [0, 0, 20, 0]
                    }
                ],
                margin: [20, 0, 20, 10]
            };
        };
    }
},
            {
    extend: 'print',
    title: '',
    className: 'dt-btn-custom',
    exportOptions: { columns: ':visible' },
    customize: function (win) {
        $(win.document.body).css('font-size', '12px');
        $(win.document.body).find('h1').remove();
        
        let genBy = '<?php echo isset($_SESSION["mobile"]) ? $_SESSION["mobile"] : (isset($_SESSION["client_mobile"]) ? $_SESSION["client_mobile"] : "User"); ?>';
        let dateStr = now.toLocaleString('en-IN');
        
        $(win.document.body).prepend(`
            <div style="margin-bottom:15px; border-bottom: 2px solid #000; padding-bottom: 10px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="width:30%; font-size:11px;">
                        <div><b>Generated By:</b> ${genBy}</div>
                        <div><b>Date:</b> ${dateStr}</div>
                    </div>
                    <div style="text-align:center; width:40%;">
                        <div style="font-size:18px; font-weight:bold;">SMART TOILET</div>
                        <div style="font-size:14px; font-weight:bold;">SMART TOILET</div>
                    </div>
                    <div style="width:30%; text-align:right;">
                        <img src="${typeof logoPath !== 'undefined' ? logoPath : (typeof companyLogoPath !== 'undefined' ? companyLogoPath : '')}" style="height:50px;">
                    </div>
                </div>
            </div>
        `);

        $(win.document.body).append(`
            <style>
            @media print {
                @page { margin-bottom: 45mm; }
                .dt-print-footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: auto;
                    border-top: 1px solid #000;
                    font-size: 10px;
                    line-height: 14px;
                    padding: 5px 10px;
                    background: #fff;
                    text-align: center;
                }
            }
            </style>
            <div class="dt-print-footer">
                <div><strong>AARYA INNOVTECH PVT. LTD.</strong> CIN : U29305MH2019PTC327551<br><strong>Nashik Office :</strong> Flat No.4A, Sayali Darshan -A-Wing, Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003.<br><strong>Mumbai Office :</strong> Flat No.C-03, The Maharashtra Chs Ltd, C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012.<br><strong>Factory :</strong> S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010.<br>+91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com</div>
            </div>
        `);
    }
}
            }
        ],
        lengthMenu: [10, 25, 50, 100]
    });

    // View employee modal
    $(document).on("click", ".view-employee", function () {
        let id = $(this).data("id");
        $.get("view_machine.php", { id: id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

});
</script>
