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

$selectedProject = $_POST['machine_id'] ?? '';
$selectedYear    = $_POST['Year_select'] ?? '';

// Current date
$currentYear = date('Y');
$currentMonth = date('m');

// If current month < April, we are in previous FY
if($currentMonth < 4){
    $fyStart = ($currentYear - 1) . "-04-01";
    $fyEnd   = $currentYear . "-03-31";
} else {
    $fyStart = $currentYear . "-04-01";
    $fyEnd   = ($currentYear + 1) . "-03-31";
}

/* ------------------------------
   FINANCIAL YEAR DATE LOGIC
--------------------------------*/
if($selectedYear == "current"){
    $from_date = $fyStart;
    $to_date   = $fyEnd;
}

elseif($selectedYear == "last"){
    $lastStart = date('Y-m-d', strtotime($fyStart . " -1 year"));
    $lastEnd   = date('Y-m-d', strtotime($fyEnd   . " -1 year"));

    $from_date = $lastStart;
    $to_date   = $lastEnd;
}

elseif($selectedYear == "previous"){
    $prevStart = date('Y-m-d', strtotime($fyStart . " -2 year"));
    $prevEnd   = date('Y-m-d', strtotime($fyEnd   . " -2 year"));

    $from_date = $prevStart;
    $to_date   = $prevEnd;
}


?>
<head>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<style>
#userTable { border:2px solid #ddd !important; }
#userTable th, #userTable td { border:1px solid #ddd !important; }
#userTable tbody tr:nth-child(odd) {  !important; }

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
 /* Reset custom active so Bootstrap color stays */
.button-group .btn.active {
  filter: brightness(90%);   /* slightly darker to show "active" */
  box-shadow: 0 0 6px rgba(0,0,0,0.3);
}

/* FY Buttons */
    .fy-buttons { display: flex; gap: 15px; flex-wrap: wrap; }
    .fy-btn {
      padding: 12px 24px; border: none; border-radius: 10px; cursor: pointer; font-size: 13px; font-weight: 600;
      transition: all 0.3s; display: flex; align-items: center; gap: 8px;
    }
    .fy-btn.current { background: linear-gradient(135deg, #00d9a5, #00b894); color: #fff; box-shadow: 0 5px 20px rgba(0,217,165,0.3); }
    .fy-btn.last { background: linear-gradient(135deg, #3498db, #2980b9); color: #fff; box-shadow: 0 5px 20px rgba(52,152,219,0.3); }
    .fy-btn.previous { background: linear-gradient(135deg, #6c757d, #5a6268); color: #fff; box-shadow: 0 5px 20px rgba(108,117,125,0.3); }
    .fy-btn:hover { transform: translateY(-2px); }
</style>
</head>

<div class="container-fluid mt-3">

<div class="card shadow">
    <div class="card-header text-center">
        <h5><b>Report Transaction Details (Machine ID Wise)</b></h5>
    </div>

    <div class="card-body">

<form method="POST" id="complaintForm">

<div class="row">

    <div class="col-md-4">
        <label><b>Machine ID:</b></label>
        <select name="machine_id" id="machine_id" class="form-control" onchange="submitForm()" required>
            <option value="">-- Select Machine ID --</option>
           <?php
                $stateQuery = "SELECT DISTINCT machine_id FROM machines WHERE client_name = '$client_name'";
                $stateResult = mysqli_query($conn, $stateQuery);
                $selectedProject = isset($_POST['machine_id']) ? $_POST['machine_id'] : '';
                while ($row = mysqli_fetch_assoc($stateResult)) {
                    $project = htmlspecialchars($row['machine_id']);
                    $isSelected = ($project === $selectedProject) ? 'selected' : '';
                    echo "<option value=\"$project\" $isSelected>$project</option>";
                }
                ?>
        </select>
    </div>

<!-- YEAR SELECT -->
    <div class="col-md-4">
        <label><b>Years Selection</b></label>
        <select name="Year_select" id="Year_select" class="form-control" onchange="submitForm()" required>
            <option value="">-- Select Years --</option>
            <option value="current"  <?= ($selectedYear=='current')?'selected':''; ?>>Current FY (2025-2026)</option>
            <option value="last"     <?= ($selectedYear=='last')?'selected':''; ?>>Last FY (2024-2025)</option>
            <option value="previous" <?= ($selectedYear=='previous')?'selected':''; ?>>Previous FY (2023-2024)</option>
        </select>
    </div>

</div>

<input type="hidden" name="from_date" id="from_date" value="<?= $from_date ?>">
<input type="hidden" name="to_date" id="to_date" value="<?= $to_date ?>">


</form>

<!-- TABLE -->
<div class="table-responsive mt-3">
<table id="userTable" class="table table-bordered">
<thead style=''>
<tr>
    <th>Sr No</th>
    <th>Machine ID</th>
    <th>Amount</th>
    <th>Mobile</th>
    <th>Pay ID</th>
    <th>Date</th>
    <th>Status</th>
</tr>
</thead>
<tbody>

<?php
if(!empty($selectedProject) && !empty($selectedYear)){

    $sql = "
    SELECT m.machine_id, m.uses_amt,
           t.mobile, t.pay_id, t.date_time, t.status 
    FROM machines m
    LEFT JOIN trans t ON m.machine_id = t.machin_id
    WHERE m.machine_id='$selectedProject' AND m.client_name = '$client_name' 
      AND t.date_time >= '$from_date 00:00:00' AND t.date_time <= '$to_date 23:59:59'
    ";

    $res = mysqli_query($conn, $sql);
    $sr = 1;

    while($row = mysqli_fetch_assoc($res)){
        echo "<tr>
            <td>".$sr++."</td>
            <td>{$row['machine_id']}</td>
            <td>{$row['uses_amt']}</td>
            <td>".($row['mobile'] ?: '-')."</td>
            <td>".($row['pay_id'] ?: '-')."</td>
            <td>".(!empty($row['date_time']) ? date('d-m-Y H:i:s', strtotime($row['date_time'])) : '-')."</td>
            <td>".($row['status'] ?: '-')."</td>
        </tr>";
    }

}
?>

</tbody>
</table>
</div>

</div>
</div>

</div>

<?php include('include/scripts.php'); ?>
<?php include('include/footer.php'); ?>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
</script>

<script>

$(document).ready(function () {

    const now = new Date();
    
        const timestamp = new Intl.DateTimeFormat('en-IN', {
    timeZone: 'Asia/Kolkata',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    hour12: false
})
.format(new Date())
.replace(/[\/,: ]/g, '_'); // make filename safe

const fileName = `Transaction Report(Machinewise)-${timestamp}`;
    const logoPath = 'smart-buddy logo.jpeg'; // ✅ SAME IMAGE FOR PDF & PRINT

    // Convert image → Base64 automatically
    getBase64FromImageUrl(logoPath, function (logoBase64) {

        $('#userTable').DataTable({

            dom:
                "<'row'<'col-md-12'B>>" +
                "<'row'<'col-md-6'l><'col-md-6 text-right'f>>" +
                "<'row'<'col-md-12'tr>>" +
                "<'row'<'col-md-5'i><'col-md-7 text-right'p>>",

            buttons: [

            /* ==================================================
           EXCEL
        ================================================== */
        {
    extend: 'excelHtml5',
    filename: fileName,
    title: null, // ✅ IMPORTANT: removes default DataTables title

    className: 'dt-btn-custom',

    customize: function (xlsx) {

        const sheet = xlsx.xl.worksheets['sheet1.xml'];
        const sheetData = $('sheetData', sheet);

        /* ==================================================
           SHIFT EXISTING ROWS DOWN BY 2
        ================================================== */
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

        /* ==================================================
           HEADER ROWS (LEFT ALIGNED)
        ================================================== */
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

        /* ==================================================
           MERGECELLS (OPTIONAL – SMALL LEFT BLOCK)
           Remove this block completely if you want NO merge
        ================================================== */
        let mergeCells = $('mergeCells', sheet);

        if (mergeCells.length === 0) {
            mergeCells = $('<mergeCells count="0"/>');
            $('worksheet', sheet).append(mergeCells);
        }

        // Small left merge (keeps text LEFT)
        mergeCells.append('<mergeCell ref="A2:C2"/>');
        mergeCells.attr('count', mergeCells.find('mergeCell').length);

        /* ==================================================
           FOOTER
        ================================================== */
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
    className: 'dt-btn-custom',
    exportOptions: { columns: ':visible' },
    customize: function (doc) {
        let imgBase64 = typeof logoBase64 !== 'undefined' ? logoBase64 : (typeof COMPANY_LOGO_BASE64 !== 'undefined' ? COMPANY_LOGO_BASE64 : '');
        let titleText = `Transaction Report(Machinewise)-${timestamp}`;
        
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
        // SPACIOUS & High Contrast PDF (8-10 rows per page)
        doc.defaultStyle.color = '#000000';
        doc.defaultStyle.fontSize = 12; // Big font for high readability
        
        if (!doc.styles) doc.styles = {};
        
        doc.styles.tableHeader = {
            fillColor: '#cccccc', 
            color: '#000000',     
            bold: true,
            fontSize: 13, // Larger header
            alignment: 'center'
        };
        doc.styles.tableBodyEven = {
            alignment: 'center',
            color: '#000000'
        };
        doc.styles.tableBodyOdd = {
            alignment: 'center',
            color: '#000000'
        };

        const tableNode = doc.content.find(c => c.table);
        if (tableNode) {
            tableNode.alignment = 'center';
            
            // THICK Solid black borders with LARGE padding for spacious rows
            tableNode.layout = {
                hLineWidth: function(i, node) { return 1.5; },
                vLineWidth: function(i, node) { return 1.5; },
                hLineColor: function(i, node) { return '#000000'; },
                vLineColor: function(i, node) { return '#000000'; },
                paddingLeft: function(i, node) { return 6; },
                paddingRight: function(i, node) { return 6; },
                paddingTop: function(i, node) { return 10; }, // LARGE padding for fewer rows per page
                paddingBottom: function(i, node) { return 10; }
            };

            tableNode.table.body.forEach((row, rowIndex) => {
                row.forEach(cell => {
                    cell.alignment = 'center';
                    cell.valign = 'middle';
                    cell.color = '#000000';
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
                        <div style="font-size:14px; font-weight:bold;">Transaction Report(Machinewise)-${timestamp}</div>
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

            ]
        });

    });
});


function submitForm(){
    $("#complaintForm").submit();
}
</script>
