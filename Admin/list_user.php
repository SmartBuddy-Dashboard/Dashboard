<?php 
require_once('include/header.php');
require_once('include/config.php');




require_once('include/navbar.php');
?>
<head>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

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
                        <h5 class="text-center"><b>Users Details</b></h5>                  
                        <a class="btn btn-primary text-right" href="add_user.php" >Add New</a>
                    </div>

    <div class="card-body">
        <div class="table-responsive">

        <?php 
        $sql = "SELECT * FROM tblusers Where role = 'Operation'";
        $result = mysqli_query($conn,$sql);

        if ($result) {

            echo "<table class='table table-bordered' id='userTable' cellspacing='0'>";

            echo "
            <thead style='background:#dbe5ec; border:1px solid #ddd;'>
                <tr>
                    <th style='border:1px solid #ddd; text-align:left; width:40px;'>Sr. No</th>
                    <th style='border:1px solid #ddd; text-align:left;'>Name</th>
                    <th style='border:1px solid #ddd; text-align:left;'>Mobile</th>
                    <th style='border:1px solid #ddd; text-align:left;'>Email </th>
                    
                    <th style='border:1px solid #ddd; text-align:left;'>Action</th>
                </tr>
            </thead>
            <tbody>";
            
            $srNo = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $name = $row['name'];
                $email = $row['email'];
                $mobile = $row['mobile'];
              

                echo "<tr>
                        <td>$srNo</td>
                        <td>$name</td>
                        <td>$mobile</td>
                        <td>$email</td>

                        
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

                                <a href='delete_user.php?deleteid=$id' 
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
                <h5 class="modal-title">User Details</h5>
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


<script>
$(document).ready(function() {


    // Edit
    $(document).on("click", ".edit-employee", function () {
        let id = $(this).data("updateid");
        $.get("edit_user.php", { updateid: id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

    // View
    $(document).on("click", ".view-employee", function () {
        let id = $(this).data("id");
        $.get("view_user.php", { id:id }, function(data){
            $("#employeeDetails").html(data);
        });
    });

});

</script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
/* ================= IMAGE → BASE64 ================= */
function getBase64FromImageUrl(url, callback) {
    const img = new Image();
    img.crossOrigin = "anonymous";
    img.onload = function () {
        const canvas = document.createElement("canvas");
        canvas.width = this.width;
        canvas.height = this.height;
        canvas.getContext("2d").drawImage(this, 0, 0);
        callback(canvas.toDataURL("image/png"));
    };
    img.src = url;
}

let COMPANY_LOGO_BASE64 = '';

$(document).ready(function() {

    const now = new Date();
    const timestamp = new Intl.DateTimeFormat('en-IN', {
        timeZone: 'Asia/Kolkata',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    }).format(now).replace(/[\/,: ]/g, '_');
    
    const fileName = `List User Report-${timestamp}`;
    const companyLogoPath = 'smart-buddy logo.jpeg';
    
    getBase64FromImageUrl(companyLogoPath, function (b64) {
        COMPANY_LOGO_BASE64 = b64;
        initTable();
    });

    function initTable() {
        var table = $('#userTable').DataTable({
            dom: "<'row'<'col-md-12 d-flex justify-content-end'B>>" +
                 "<'row'<'col-md-6'l><'col-md-6 text-right'f>>" +
                 "<'row'<'col-md-12'tr>>" +
                 "<'row'<'col-md-5'i><'col-md-7 text-right'p>>",

            buttons: [
                {
                    extend: 'excelHtml5',
                    filename: fileName,
                    title: null,
                    exportOptions: { columns: ':not(:last-child)' },
                    customize: function (xlsx) {
                        const sheet = xlsx.xl.worksheets['sheet1.xml'];
                        const sheetData = $('sheetData', sheet);
                        sheetData.find('row').each(function () {
                            const r = parseInt($(this).attr('r'));
                            $(this).attr('r', r + 2);
                            $(this).find('c').each(function () {
                                const cellRef = $(this).attr('r');
                                const col = cellRef.replace(/[0-9]/g, '');
                                const row = parseInt(cellRef.replace(/[A-Z]/g, '')) + 2;
                                $(this).attr('r', col + row);
                            });
                        });
                        const headerRows = `
                            <row r="1">
                                <c r="A1" t="inlineStr">
                                    <is><t>${now.toLocaleString('en-IN')}</t></is>
                                </c>
                            </row>
                            <row r="2">
                                <c r="A2" t="inlineStr" s="51">
                                    <is><t>SMART TOILET</t></is>
                                </c>
                            </row>
                        `;
                        sheetData.prepend(headerRows);
                        let mergeCells = $('mergeCells', sheet);
                        if (mergeCells.length === 0) {
                            mergeCells = $('<mergeCells count="0"/>');
                            $('worksheet', sheet).append(mergeCells);
                        }
                        mergeCells.append('<mergeCell ref="A2:C2"/>');
                        mergeCells.attr('count', mergeCells.find('mergeCell').length);
                        const footerRow = `
                            <row>
                                <c t="inlineStr">
                                    <is>
                                        <t>AARYA INNOVTECH PVT. LTD. CIN : U29305MH2019PTC327551 - Nashik Office : Flat No.4A, Sayali Darshan -A-Wing. Radha Nagar, Makhamalabad Road, Panchavati, Nashik, Maharashtra-422003. - Mumbai Office : Flat No.C-03, The Maharashtra Chs Ltd. C Wing Ground Floor, Ambekar Nagar, G. D. Ambekar Mark, Parel Mumbai City, Maharashtra - 400012. - Factory : S-27, Near Emerson, Ambad MIDC, Nashik, Maharashtra - 422010. - +91 8806796868 / +91 9923810197 | sales@smartbuddy.co.in | www.aaryainnovtech.com</t>
                                    </is>
                                </c>
                            </row>
                        `;
                        sheetData.append(footerRow);
                    }
                },
                {
                    extend: 'pdfHtml5',
                    filename: fileName,
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: '',
                    exportOptions: { columns: ':not(:last-child)' },
                    customize: function (doc) {
                        let imgBase64 = COMPANY_LOGO_BASE64;
                        let headerCols = [
                            {
                                width: '30%',
                                text: [
                                    { text: 'Generated By: ', bold: true },
                                    { text: '<?php echo isset($_SESSION["mobile"]) ? $_SESSION["mobile"] : (isset($_SESSION["client_mobile"]) ? $_SESSION["client_mobile"] : "User"); ?>\n' },
                                    { text: 'Date: ', bold: true },
                                    { text: now.toLocaleString('en-IN') }
                                ],
                                fontSize: 9,
                                alignment: 'left'
                            },
                            {
                                width: imgBase64 ? '40%' : '70%',
                                stack: [
                                    { text: 'SMART TOILET', alignment: 'center', fontSize: 16, bold: true },
                                    { text: fileName, alignment: 'center', fontSize: 12, bold: true }
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
                },
                {
                    extend: 'print',
                    title: '',
                    exportOptions: { columns: ':not(:last-child)' },
                    customize: function (win) {
                        $(win.document.body).css('font-size', '12px');
                        $(win.document.body).find('h1').remove();
                        let genBy = '<?php echo isset($_SESSION["mobile"]) ? $_SESSION["mobile"] : (isset($_SESSION["client_mobile"]) ? $_SESSION["client_mobile"] : "User"); ?>';
                        let dateStr = now.toLocaleString('en-IN');
                        $(win.document.body).prepend(`
                            <div style="margin-bottom:15px; border-bottom: 2px solid #000; padding-bottom: 10px;">
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <div style="width:30%; font-size:11px;">
                                        <div><b>Generated By:</b> \${genBy}</div>
                                        <div><b>Date:</b> \${dateStr}</div>
                                    </div>
                                    <div style="text-align:center; width:40%;">
                                        <div style="font-size:18px; font-weight:bold;">SMART TOILET</div>
                                        <div style="font-size:14px; font-weight:bold;">\${fileName}</div>
                                    </div>
                                    <div style="width:30%; text-align:right;">
                                        <img src="\${companyLogoPath}" style="height:50px;">
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
            ],
            lengthMenu: [10, 25, 50, 100]
        });
    }
});
</script>

