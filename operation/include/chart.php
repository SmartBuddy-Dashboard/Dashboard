<?php 
require_once('include/header.php');
require_once('include/config.php');
require_once('include/navbar.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Machine Transaction Analysis</title>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
body{
  font-family:Poppins,sans-serif;
  background:#eef2f7
}

.card{
  border-radius:20px;
  box-shadow:0 8px 16px rgba(0,0,0,.1)
}

#complaintForm{
  background:#fff;
  padding:20px;
  border-radius:15px;
  margin-bottom:20px
}

/* Chart wrapper */
.chart-box{
  width:100%;
  height:420px;
  margin-bottom:30px;
}

/* Canvas */
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
  font-weight:600
}

/* 📱 MOBILE */
@media (max-width:768px){

  h5{font-size:16px}
  h6{font-size:14px}

  .container-fluid{
    padding-left:8px;
    padding-right:8px;
  }

  #complaintForm{
    padding:15px
  }

  .chart-box{
    height:300px;
  }

  .text-right{
    text-align:center!important;
  }

  .btn-custom{
    width:100%;
    margin:5px 0;
  }
}

/* 🖨️ PRINT */
@media print{
  nav,.navbar,.sidebar,footer,form,button,.btn,.card-header{
    display:none!important
  }
  canvas{
    page-break-inside:avoid
  }
}
</style>
</head>

<body>
<div class="container-fluid mt-3">
<div class="card">
<div class="card-header text-center">
<h5><b>📊 Machine Transaction Analysis</b></h5>
<p class="text-muted">Monthly Coin & UPI analysis</p>
</div>

<div class="card-body">
<form method="post" id="complaintForm">
<div class="form-row">
<div class="form-group col-md-4 col-12">
<label><b>Select Machine ID</b></label>
<select class="form-control" name="machine_id" onchange="this.form.submit()" required>
<option value="">-- Select Machine --</option>
<?php
$res = mysqli_query($conn,"SELECT DISTINCT machine_id FROM machines ORDER BY machine_id");
$selected = $_POST['machine_id'] ?? '';
while($r=mysqli_fetch_assoc($res)){
  $sel = ($r['machine_id']==$selected)?'selected':'';
  echo "<option $sel>{$r['machine_id']}</option>";
}
?>
</select>
</div>
</div>
</form>

<?php
if(!empty($_POST['machine_id'])){

$machine_id = mysqli_real_escape_string($conn,$_POST['machine_id']);
$months = ['Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar'];
$monthNumbers = [4,5,6,7,8,9,10,11,12,1,2,3];

$coinAmount=$upiAmount=$coinCount=$upiCount=[];

foreach($monthNumbers as $m){

  $coinAmount[] = (float)(mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(trans_amt) t FROM trans WHERE machin_id='$machine_id' AND trans_mode='coin' AND status='success' AND MONTH(date_time)='$m'"))['t'] ?? 0);

  $upiAmount[] = (float)(mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT SUM(trans_amt) t FROM trans WHERE machin_id='$machine_id' AND trans_mode='upi' AND status='success' AND MONTH(date_time)='$m'"))['t'] ?? 0);

  $coinCount[] = (int)(mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) c FROM trans WHERE machin_id='$machine_id' AND trans_mode='coin' AND status='success' AND MONTH(date_time)='$m'"))['c'] ?? 0);

  $upiCount[] = (int)(mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) c FROM trans WHERE machin_id='$machine_id' AND trans_mode='upi' AND status='success' AND MONTH(date_time)='$m'"))['c'] ?? 0);
}
?>

<div class="text-right mb-3">
<button class="btn btn-primary btn-custom" onclick="window.print()">🖨️ Print</button>
<button class="btn btn-danger btn-custom" onclick="downloadPDF()">📄 PDF</button>
</div>

<h6 class="text-center text-primary">💰 Yearly Transaction Amount — <b><?= $machine_id ?></b></h6>
<div class="chart-box">
  <canvas id="amountChart"></canvas>
</div>

<h6 class="text-center text-warning">⚙️ Yearly Usage Count — <b><?= $machine_id ?></b></h6>
<div class="chart-box">
  <canvas id="usageChart"></canvas>
</div>

<script>
const months = <?= json_encode($months) ?>;
const coinAmount = <?= json_encode($coinAmount) ?>;
const upiAmount  = <?= json_encode($upiAmount) ?>;
const coinCount  = <?= json_encode($coinCount) ?>;
const upiCount   = <?= json_encode($upiCount) ?>;

new Chart(amountChart,{
  type:'bar',
  data:{
    labels:months,
    datasets:[
      {
        label:'Coin Amount',
        data:coinAmount,
        backgroundColor:'rgba(41,128,185,0.9)', // Blue
        barPercentage:1
      },
      {
        label:'UPI Amount',
        data:upiAmount,
        backgroundColor:'rgba(39,174,96,0.9)', // Green
        barPercentage:1
      }
    ]
  },
  options:{
    responsive:true,
    maintainAspectRatio:false,
    scales:{
      x:{
        categoryPercentage:0.6   // space between months only
      },
      y:{
        beginAtZero:true
      }
    }
  }
});


new Chart(usageChart,{
  type:'bar',
  data:{
    labels:months,
    datasets:[
      {
        label:'Coin Usage',
        data:coinCount,
        backgroundColor:'rgba(41,128,185,0.9)', // Blue
        barPercentage:1
      },
      {
        label:'UPI Usage',
        data:upiCount,
        backgroundColor:'rgba(39,174,96,0.9)', // Green
        barPercentage:1
      }
    ]
  },
  options:{
    responsive:true,
    maintainAspectRatio:false,
    plugins:{
      tooltip:{
        callbacks:{
          label:(ctx)=>`${ctx.dataset.label}: ${ctx.raw} times`
        }
      }
    },
    scales:{
      x:{
        categoryPercentage:0.6
      },
      y:{
        beginAtZero:true,
        ticks:{ stepSize:1 }
      }
    }
  }
});


async function downloadPDF(){
  const {jsPDF}=window.jspdf;
  const pdf=new jsPDF();
  const canvas=await html2canvas(document.body,{scale:2});
  pdf.addImage(canvas.toDataURL(),'PNG',10,10,190,0);
  pdf.save('Machine_Analysis_<?= $machine_id ?>.pdf');
}
</script>

<?php } ?>
<script>
document.querySelectorAll('a[href]').forEach(a => {
    a.addEventListener('click', function () {
        window.location = this.href;
    });
});
</script>
</div>
</div>
</div>

<?php include('include/footer.php'); ?>
</body>
</html>
