<?php
include_once('include/config.php');

// -----------------------------
// DIRECT SESSION CHECK
// -----------------------------
if (!isset($_SESSION['mobile'])) {
    echo "<script>alert('User not logged in!'); window.location='index.php';</script>";
    exit();
}

$mobile = $_SESSION['mobile'];

// Optional: check inactivity timeout
$inactive = 900;  // 2 minutes

if (isset($_SESSION['timeout'])) {
    if (time() - $_SESSION['timeout'] > $inactive) {

        // Update login status in DB
        if (isset($_SESSION['user_id'])) {
            $uid = $_SESSION['user_id'];
            mysqli_query($conn, "UPDATE tblusers SET is_logged_in = 0 WHERE id = '$uid'");
        }

        session_unset();
        session_destroy();

        echo "<script>alert('Session expired!'); window.location='index.php';</script>";
        exit();
    }
}

$_SESSION['timeout'] = time();
// -----------------------------



?>
<!DOCTYPE html>
<html lang="en">
<?php include 'include/head.php'; ?>
<head>
        
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
         .modal-dialog {
    max-width: 50% !important; /* Increase the width to make it larger */
    margin: 1.75rem auto; /* Center the modal */
}

.modal-content {
    border-radius: 10px; /* Optional: rounded corners */
}
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container-fluid {
    margin: 0 auto;
    width: 100% !important;
    max-width: 100% !important;
    position: relative;
    padding-left: 0;
    padding-right: 0;
}

.table-responsive, 
#example {
    width: 100% !important;
}

        .add-button-container {
            margin-bottom: 10px;
            position: absolute;
            top: 0;
            right: 0;
        }

        .table {
            background-color: #fff;
        }

        th,
        td {
            text-align: center;
        }

        .edit-employee,
        .delete-employee {
            text-decoration: none;
            cursor: pointer;
        }

        .edit-employee:hover,
        .delete-employee:hover {
            text-decoration: underline;
        }

        .modal-dialog {
            max-width: 500px;
        }
.btn-group-custom {
    display: flex;
    justify-content: center;  /* center align */
    gap: 10px;                /* spacing between buttons */
}

        /* Center the specific heading */
        .textalign-center {
            text-align: center !important; /* Add !important to override any conflicting CSS */
        }
          .center-text {
    text-align: center;
  }

.btn-gradient {
    background: linear-gradient(135deg, #00d9a5, #00b894);
    border: none;
    color: #fff;
    font-weight: 500;
}

.btn-gradient:hover {
    opacity: 0.9;
    color: #fff;
}
       
    </style>
    </head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
<?php include 'include/sidebar.php'; ?>
<?php include 'include/header.php'; ?>

  <!-- [ Main Content ] start -->
  <div class="pc-container">
    <div class="pc-content">
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <div class="page-header-title">
                <h5 class="m-b-10">Client details</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
        
      <div class="row">
          <div class="card">
            <div class="card-body">
              <div class="container-fluid">
        <div class="row justify-content-center">

            <div class="row justify-content-end">
               <div class="col-auto">
    <a href="add_client.php" class="btn btn-gradient">
        <i class="fas fa-plus"></i> Add New
    </a>
</div>
            </div>


            <div class="col-lg-12">
                  <div class="table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
         <thead class="table-dark">
          <tr>
            <th scope="col" class="textalign-center">Sr.No</th>
            <th scope="col" class="textalign-center">Client Name </th>
            
             <th scope="col" class="textalign-center">Client City</th>
              <th scope="col" class="textalign-center">Contact Name</th>
               <th scope="col" class="textalign-center">Contact Mobile</th>
               
            <th scope="col" class="textalign-center">Action</th>
          </tr>
        </thead>
        <tbody>
           
          <?php
          $query = "SELECT * FROM clients";
          $result = mysqli_query($conn, $query);
          $srNo = 1;
          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              $id = $row['id'];
              $client_name = $row['client_name'];
              $clinet_district   = $row['clinet_district'];
               $client_city   = $row['client_city'];
                $contact_person   = $row['contact_person'];
                 $contact_mobile   = $row['contact_mobile'];



              echo "<tr>";
              echo "<td class='textalign-center'>{$srNo}</td>";
              echo "<td class='textalign-center'>{$client_name}</td>";
             
              echo "<td class='textalign-center'>{$client_city}</td>";
              echo "<td class='textalign-center'>{$contact_person}</td>";
              echo "<td class='textalign-center'>{$contact_mobile}</td>";

             echo "<td class='textalign-center'>";
echo "<div class='action-buttons btn-group-custom'>";

// Edit button
echo "<a href='#' class='edit-employee btn btn-info btn-sm' 
        data-toggle='modal' data-target='#employeeModal' 
        data-updateid='$id' title='Update'>
        <i class='fas fa-edit'></i>
      </a>";




// View button
echo '<a href="#" class="view-employee btn btn-success btn-sm" 
        data-toggle="modal" data-target="#employeeModal" 
        data-id="' . $id . '" title="View">
        <i class="fas fa-eye"></i>
      </a>';

// Delete button
echo '<a href="delete_client.php?deleteid=' . $id . '" 
        class="btn btn-danger btn-sm" title="Delete" 
        onclick="return confirm(\'Are you sure, you want to delete?\')">
        <i class="fas fa-trash"></i>
      </a>';

echo "</div>";

echo "</td>";

              echo "</tr>";
              $srNo++;
            }
          } 
          ?>
        </tbody>
      </table>
</div>


            </div>
        </div>
    </div>


    <div class="modal fade" id="employeeModal" tabindex="-1" role="dialog" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeModalLabel">Client Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Employee details fetched by AJAX will be displayed here -->
                    <div id="employeeDetails"></div>
                </div>
<div class="modal-footer">
    
</div>

            </div>
        </div>
    </div>
       
              

                
                
                
                
              
              
        </div>
          </div>
      </div>
    </div>
  </div>

<?php include 'include/footer.php'; ?>
<?php include 'include/jsc.php'; ?>
            
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

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
    
    const fileName = `Client List-${timestamp}`;
    const companyLogoPath = 'smart-buddy logo.jpeg';
    
    getBase64FromImageUrl(companyLogoPath, function (b64) {
        COMPANY_LOGO_BASE64 = b64;
        initTable();
    });

    function initTable() {
        var table = $('#example').DataTable({
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
                                        <div><b>Generated By:</b> ${genBy}</div>
                                        <div><b>Date:</b> ${dateStr}</div>
                                    </div>
                                    <div style="text-align:center; width:40%;">
                                        <div style="font-size:18px; font-weight:bold;">SMART TOILET</div>
                                        <div style="font-size:14px; font-weight:bold;">${fileName}</div>
                                    </div>
                                    <div style="width:30%; text-align:right;">
                                        <img src="${companyLogoPath}" style="height:50px;">
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
    
    // Edit fetch
    $(document).on("click", ".edit-employee", function () {
        var id = $(this).data("updateid");
        $.ajax({
            type: "GET",
            url: "edit_client.php",
            data: { updateid: id },
            success: function (response) {
                $("#employeeDetails").html(response);
            }
        });
    });

    // When a View link is clicked
    $(document).on("click", ".view-employee", function () {
        var id = $(this).data("id");
        $.ajax({
            type: "GET",
            url: "view_clinet.php",
            data: { id: id },
            success: function (response) {
                $("#employeeDetails").html(response);
            }
        });
    });
});
</script>
            
           
    

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
        


    
</body>
</html>
