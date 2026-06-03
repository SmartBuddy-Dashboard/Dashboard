<?php 
require_once('include/header.php');
require_once('include/navbar.php');
require_once('include/config.php');

/* ------------------------------
   GET POST VALUES
--------------------------------*/




$machine_id   = $_POST['machine_id'] ?? '';
$selectedYear = $_POST['year'] ?? '';

$from_date = $_POST['from_date'] ?? '';
$to_date   = $_POST['to_date'] ?? '';

/* ------------------------------
   FINANCIAL YEAR CALCULATION
--------------------------------*/
$currentYear  = date('Y');
$currentMonth = date('m');

if ($currentMonth < 4) {
    $fyStart = ($currentYear - 1) . "-04-01";
    $fyEnd   = $currentYear . "-03-31";
} else {
    $fyStart = $currentYear . "-04-01";
    $fyEnd   = ($currentYear + 1) . "-03-31";
}

/* ------------------------------
   DATE FILTER LOGIC
--------------------------------*/

if(!empty($selectedYear)){

    if ($selectedYear === "current") {
        $from_date = $fyStart;
        $to_date   = $fyEnd;
    } 
    elseif ($selectedYear === "last") {
        $from_date = date('Y-m-d', strtotime($fyStart . " -1 year"));
        $to_date   = date('Y-m-d', strtotime($fyEnd   . " -1 year"));
    } 
    elseif ($selectedYear === "previous") {
        $from_date = date('Y-m-d', strtotime($fyStart . " -2 year"));
        $to_date   = date('Y-m-d', strtotime($fyEnd   . " -2 year"));
    }

}
$companyLogo = 'smart-buddy logo.jpeg'; // path relative to this file
?>

<style>
/* ---------- Screen Styling ---------- */
.card{
  font-family:Poppins,sans-serif;
  background:#eef2f7;
  border-radius:20px;
  box-shadow:0 8px 16px rgba(0,0,0,.1);
}
#complaintForm{
  background:#fff;
  padding:20px;
  border-radius:15px;
  margin-bottom:20px;
}
.chart-box{
  width:100%;
  height:420px;
  margin-bottom:30px;
}
canvas{
  background:#fff;
  border-radius:15px;
  box-shadow:0 4px 10px rgba(0,0,0,.08);
  padding:10px;
  width:100%!important;
  height:100%!important;
}
.btn-custom{
  border-radius:25px;
  padding:8px 20px;
  font-weight:600;
}
@media (max-width:768px){
  h5{font-size:16px}
  h6{font-size:14px}
  .chart-box{height:300px}
  .btn-custom{width:100%;margin:5px 0}
}

/* ---------- Print Styling ---------- */
#printHeader, #printFooter {
    display: none;
}

@media print {

  /* Hide screen-only elements */
  nav, .navbar, .sidebar, button, .btn, form, .card-header {
    display: none !important;
  }

  /* Page margins */
  @page {
    margin: 45mm 12mm 30mm 12mm;
  }

  body {
    padding: 0;
    margin: 0;
  }

  /* ===== PRINT HEADER ===== */
  #printHeader {
    display: block !important;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 40mm;
    background: #fff;
    border-bottom: 1px solid #000;
    padding: 8mm 10mm;
    z-index: 9999;
  }

  #printHeader .header-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  #printHeader .timestamp {
    font-size: 11px;
    color: #444;
    white-space: nowrap;
  }

  #printHeader .title {
    font-size: 22px;        /* BIGGER heading */
    font-weight: 800;
    color: #1ABC9C;
    text-align: center;
    flex: 1;
    letter-spacing: 0.5px;
}

  #printHeader img {
    height: 28mm;
    max-width: 45mm;
    object-fit: contain;
  }

  /* ===== PRINT FOOTER ===== */
  #printFooter {
    display: block !important;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 18mm;
    text-align: center;
    font-size: 10px;
    color: #555;
    border-top: 1px solid #000;
    padding-top: 5mm;
    background: #fff;
    z-index: 9999;
  }

  /* Push content below header */
  #pdfContent {
    margin-top: 42mm;
  }
}
/* Hide print header when generating PDF */
.pdf-mode #printHeader {
    display: none !important;
}

</style>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    
    <input type="hidden" name="machine_id" value="<?= $machine_id; ?>">
    <input type="hidden" name="year" value="<?= $selectedYear; ?>">
    <input type="hidden" name="from_date" value="<?= $from_date; ?>">
    <input type="hidden" name="to_date" value="<?= $to_date; ?>">

   



<div class="container-fluid">
<div class="card shadow mb-4">
<div class="card-header text-center">
<h5><b>📊 Machine Transactions / Usages</b></h5>
<p class="text-muted">Yearly Report</p>
</div>

<div class="card-body">


<?php
if ($machine_id && $from_date && $to_date):

$machine_id_safe = mysqli_real_escape_string($conn, $machine_id);

/* ================= MACHINE + CLIENT INFO ================= */
$machineInfo = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        m.client_name,
        m.project_name,
        m.address,
        
        c.client_logo
    FROM machines m
    LEFT JOIN clients c ON c.client_name = m.client_name
    WHERE m.machine_id = '$machine_id_safe'
    LIMIT 1
"));


$client_name     = $machineInfo['client_name'] ?? '';
$project_name    = $machineInfo['project_name'] ?? '';
$client_address  = $machineInfo['address'] ?? '';

$client_logo     = !empty($machineInfo['client_logo'])
                    ? 'uploads/' . $machineInfo['client_logo']
                    : '';

/* ================= MONTH SETUP ================= */
$months       = ['Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar'];
$monthNumbers = [4,5,6,7,8,9,10,11,12,1,2,3];

$coinAmount = $upiAmount = $coinCount = $upiCount = $freeCount = [];

/* ================= TRANSACTION DATA ================= */
foreach ($monthNumbers as $m) {

    $coinAmount[] = (float)(
        mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT SUM(trans_amt) t FROM trans
            WHERE machin_id='$machine_id_safe'
            AND trans_mode='coin'
            AND date_time BETWEEN '$from_date' AND '$to_date'
            AND MONTH(date_time)='$m'
        "))['t'] ?? 0
    );

    $upiAmount[] = (float)(
        mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT SUM(trans_amt) t FROM trans
            WHERE machin_id='$machine_id_safe'
            AND trans_mode='upi'
            AND status='success'
            AND date_time BETWEEN '$from_date' AND '$to_date'
            AND MONTH(date_time)='$m'
        "))['t'] ?? 0
    );

    $coinCount[] = (int)(
        mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT COUNT(*) c FROM trans
            WHERE machin_id='$machine_id_safe'
            AND trans_mode='coin'
            AND date_time BETWEEN '$from_date' AND '$to_date'
            AND MONTH(date_time)='$m'
        "))['c'] ?? 0
    );

    $upiCount[] = (int)(
        mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT COUNT(*) c FROM trans
            WHERE machin_id='$machine_id_safe'
            AND trans_mode='upi'
            AND status='success'
            AND date_time BETWEEN '$from_date' AND '$to_date'
            AND MONTH(date_time)='$m'
        "))['c'] ?? 0
    );

    $freeCount[] = (int)(
        mysqli_fetch_assoc(mysqli_query($conn,"
            SELECT COUNT(*) c FROM trans
            WHERE machin_id='$machine_id_safe'
            AND trans_mode='button'
            AND date_time BETWEEN '$from_date' AND '$to_date'
            AND MONTH(date_time)='$m'
        "))['c'] ?? 0
    );
}
?>

<div class="text-right mb-3">
<button class="btn btn-primary btn-custom" onclick="window.print()">🖨️ Print</button>
<button class="btn btn-danger btn-custom" onclick="downloadPDF()">📄 PDF</button>
  
        <button class="btn btn-secondary btn-custom"
                onclick="window.location.href='report_details.php'">
            <i class="fas fa-arrow-left"></i>
        </button>

</div>


<div id="printHeader">
  <div class="header-row">

    
    <!-- LEFT : CLIENT LOGO ONLY -->
    <div style="width:180px; text-align:left;">
      <?php if (!empty($client_logo)): ?>
        <img src="<?= htmlspecialchars($client_logo) ?>"
             alt="Client Logo"
             style="height:22mm; max-width:60mm; object-fit:contain;">
      <?php endif; ?>
    </div>

    <!-- CENTER : Title (BIGGER) -->
    <div class="title">
      Smart Toilet
    </div>

    <!-- RIGHT : Logo -->
    <div>
      <img src="<?= $companyLogo ?>" alt="Company Logo">
    </div>

  </div>
</div>




<div id="printFooter">
  <div style="display:flex; justify-content:space-between; align-items:center; padding:0 10mm;">
    
    <!-- LEFT (if needed, empty for now) -->
    <div></div>

    <!-- RIGHT : Timestamp -->
<div style="font-weight: bold; font-size: 14px;">
    <?php
    date_default_timezone_set('Asia/Kolkata'); // Set timezone to Kolkata
    echo date('d/m/Y H:i');
    ?>
</div>

  </div>
</div>


<div id="pdfContent">

<div class="text-center mb-4" style="border-bottom:2px solid #ddd; padding-bottom:15px;">
    <h5 style="margin-bottom:5px; font-weight:700; color:#2C3E50;">
        <span><?= htmlspecialchars($client_name) ?></span> |
        <span><?= htmlspecialchars($project_name) ?></span> -
        <span style="color:#1ABC9C;"><?= htmlspecialchars($machine_id) ?></span>
    </h5>

    <p style="margin-bottom:5px; font-size:14px; color:#7F8C8D; font-style:italic;">
        <?= htmlspecialchars($client_address) ?>
    </p>

    <h6 style="margin-top:8px; font-weight:600; color:#E67E22;">
        💰 Yearly Transaction
    </h6>
</div>

<div class="chart-box"><canvas id="amountChart"></canvas></div>

<h6 class="text-center text-warning">⚙️ Yearly Usage Count — <b><?= $machine_id ?></b></h6>
<div class="chart-box"><canvas id="usageChart"></canvas></div>

</div>
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
const months = <?= json_encode($months) ?>;
const coinAmount = <?= json_encode($coinAmount) ?>;
const upiAmount  = <?= json_encode($upiAmount) ?>;
const coinCount  = <?= json_encode($coinCount) ?>;
const upiCount   = <?= json_encode($upiCount) ?>;
const freeCount  = <?= json_encode($freeCount) ?>;

new Chart(amountChart,{
  type:'bar',
  data:{
    labels:months,
    datasets:[
      {label:'Coin Amount', data:coinAmount, backgroundColor:'rgba(41,128,185,0.9)', barPercentage:1},
      {label:'UPI Amount', data:upiAmount, backgroundColor:'rgba(39,174,96,0.9)', barPercentage:1}
    ]
  },
  options:{responsive:true, maintainAspectRatio:false, scales:{x:{categoryPercentage:0.6},y:{beginAtZero:true}}}
});

new Chart(usageChart,{
  type:'bar',
  data:{
    labels:months,
    datasets:[
      {label:'Coin Usage', data:coinCount, backgroundColor:'rgba(41,128,185,0.9)', barPercentage:1},
      {label:'UPI Usage', data:upiCount, backgroundColor:'rgba(39,174,96,0.9)', barPercentage:1},
      {label:'Free Usage', data:freeCount, backgroundColor:'rgba(241,196,15,0.9)', barPercentage:1}
    ]
  },
  options:{
    responsive:true, maintainAspectRatio:false,
    plugins:{tooltip:{callbacks:{label:(ctx)=>`${ctx.dataset.label}: ${ctx.raw} times`}}},
    scales:{x:{categoryPercentage:0.6},y:{beginAtZero:true, ticks:{stepSize:1}}}
  }
});

async function downloadPDF() {

    // 1️⃣ Hide print header ONLY for PDF
    document.body.classList.add('pdf-mode');

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p', 'mm', 'a4');

    const content = document.getElementById('pdfContent');

    const canvas = await html2canvas(content, {
        scale: 2,
        useCORS: true
    });

    const imgData = canvas.toDataURL('image/png');

    const pdfWidth = pdf.internal.pageSize.getWidth();
    const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

    /* ===== TIMESTAMP ===== */
    const now = new Date();

    const displayTime = new Intl.DateTimeFormat('en-IN', {
        timeZone: 'Asia/Kolkata',
        dateStyle: 'medium',
        timeStyle: 'short'
    }).format(now);

    const fileTimestamp = new Intl.DateTimeFormat('en-IN', {
        timeZone: 'Asia/Kolkata',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    }).format(now).replace(/[\/,:\s]/g, '-');

  /* ===== PDF HEADER ===== */
pdf.setFontSize(18);
pdf.setFont('helvetica', 'bold');

/* CENTER TITLE */
pdf.text('Smart Toilet', pdfWidth / 2, 20, { align: 'center' });

/* LEFT : CLIENT LOGO */
<?php if (!empty($client_logo)): ?>
const clientLogo = new Image();
clientLogo.src = "<?= $client_logo ?>";
await new Promise(resolve => clientLogo.onload = resolve);

pdf.addImage(clientLogo, 'PNG', 10, 8, 20, 15);
<?php endif; ?>

/* RIGHT : COMPANY LOGO */
const companyLogo = new Image();
companyLogo.src = "<?= $companyLogo ?>";
await new Promise(resolve => companyLogo.onload = resolve);

pdf.addImage(companyLogo, 'PNG', pdfWidth - 35, 8, 25, 15);

/* HEADER LINE */
pdf.setLineWidth(0.5);
pdf.line(10, 25, pdfWidth - 10, 25);

    /* ===== CONTENT ===== */
    pdf.addImage(imgData, 'PNG', 0, 30, pdfWidth, pdfHeight);

    /* ===== FOOTER ===== */
    pdf.setFontSize(10);
    pdf.setFont('helvetica', 'normal');
    pdf.text(
        "Copyright © Aarya Group of Industries, Nashik, Maharashtra, India.",
        pdfWidth / 2,
        pdf.internal.pageSize.getHeight() - 10,
        { align: 'center' }
    );
    // RIGHT timestamp
pdf.text(
  displayTime,
  pdfWidth - 10,
  pdf.internal.pageSize.getHeight() - 10,
  { align: 'right' }
);

    pdf.save(`Chart Report-${fileTimestamp}.pdf`);

    // 2️⃣ Restore print header after PDF
    document.body.classList.remove('pdf-mode');
}

</script>

<?php endif; ?>
</div>
</div>
</div>

<?php
include('include/scripts.php');
include('include/footer.php');
?>
