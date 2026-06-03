<?php 
require_once('include/header.php');
require_once('include/config.php');
require_once('include/navbar.php');

// ✅ Check session variables properly
if (!isset($_SESSION['mobile']) || !isset($_SESSION['client_name'])) {
    header("Location: ../index.php?error=unauthorized"); 
    exit();
}

$mobile = $_SESSION['mobile'];
$client_name = $_SESSION['client_name'];

?>
<head>
    <!-- DataTables Buttons CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <style>
        /* Custom Neon Buttons */
        .dt-btn-custom {
            padding: 8px 15px;
            border-radius: 8px;
            background: rgba(6, 182, 212, 0.1) !important;
            border: 1px solid #06B6D4 !important;
            color: var(--text-main) !important;
            font-size: 13px;
            font-weight: 600;
            margin-right: 6px;
            transition: all 0.3s ease;
        }
        .dt-btn-custom:hover {
            background: var(--primary-color) !important;
            color: #fff !important;
        }
        
        /* Live Pulsing Animation */
        @keyframes neonPulse {
            0% { box-shadow: inset 0 0 0px #10B981, 0 0 0px #10B981; background-color: transparent; }
            50% { box-shadow: inset 0 0 15px #10B981, 0 0 15px #10B981; background-color: rgba(16, 185, 129, 0.2); color: #10B981; font-weight: bold; border-radius: 6px; }
            100% { box-shadow: inset 0 0 0px #10B981, 0 0 0px #10B981; background-color: transparent; }
        }
        .live-pulse {
            animation: neonPulse 2s ease-out;
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
                        <h5 class="text-center"><b>Project details</b></h5>                  
                     
                    </div>

    <div class="card-body">
        <div class="table-responsive">

        <?php 
        $sql = "
SELECT DISTINCT 
    p.*, 
    m.client_name 
FROM 
    projects p
LEFT JOIN 
    machines m 
ON 
    p.project_name = m.project_name
WHERE 
    m.client_name = '$client_name'
";
        $result = mysqli_query($conn,$sql);

        if ($result) {

            echo "<table class='table table-bordered' id='userTable' cellspacing='0' style='border:2px solid #ddd;'>";
            echo "
            <thead style='border:1px solid #ddd;'>
                <tr>
                    <th style='border:1px solid #ddd;'>Sr. No</th>
                    <th style='border:1px solid #ddd;'>Project Name</th>
                    <th style='border:1px solid #ddd;'>Project Starts</th>
                  <th style='border:1px solid #ddd;'>Project Ends</th>
                    <th style='border:1px solid #ddd;'>Action</th>
                </tr>
            </thead>
            <tbody>";
            
            $srNo = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                 $id = $row['id'];
              $project_name = $row['project_name'];
           
               $project_starts   = $row['project_starts'];
                $project_end   = $row['project_end'];

                    // DATE FORMAT (DMY)
    $start_date = !empty($project_starts) ? date("d-m-Y", strtotime($project_starts)) : '-';
    $end_date   = !empty($project_end) ? date("d-m-Y", strtotime($project_end)) : '-';

                echo "<tr>
                        <td>$srNo</td>
                        <td>$project_name</td>
                         <td>$start_date</td>
            <td>$end_date</td>
                        
                        
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

<!-- Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Project Details</h5>
                <button type='button' class='close' data-dismiss='modal'>×</button>
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
const fileName = `Project_Details_${timestamp}`;

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
}
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
        $.get("view_project.php", { id: id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

    // Enterprise Real-Time Live Updates via SSE
    if (typeof(EventSource) !== "undefined") {
        var source = new EventSource("live_tank_stream.php");
        source.onmessage = function(event) {
            try {
                if (!event.data || event.data.trim() === '') return; // FIX: Ignore empty packets
                var data = JSON.parse(event.data);
                if (typeof data !== 'object' || data === null) throw new Error("Not a JSON object");
                
                console.log("Live IoT Update Received:", data);
                // Example UI update: Highlights and updates UI dynamically if mapped
                var cell = $('td[data-machine-id="' + data.machine_id + '"]');
                if(cell.length) {
                    cell.text("Live: " + data.received_time);
                    
                    // Trigger the Neon CSS Animation
                    cell.removeClass("live-pulse");
                    void cell[0].offsetWidth; // trigger reflow to restart animation
                    cell.addClass("live-pulse");
                }
            } catch(e) {
                console.warn("Fault Tolerant SSE: Ignored malformed data packet.", e.message);
            }
        };
        source.onerror = function(event) {
            console.log("EventSource connection lost/reconnecting.");
        };
    } else {
        console.warn("Browser does not support server-sent events for real-time tracking.");
    }

});
</script>
