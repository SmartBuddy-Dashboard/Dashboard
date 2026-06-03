<?php 
require_once('include/header.php');
require_once('include/navbar.php');
require_once('include/config.php');

if (!isset($_POST['machineid'])) {
    die("Invalid QR data.");
}

$machineId = mysqli_real_escape_string($conn, $_POST['machineid']);

/* FETCH DB ID */
$id = 0;
$res = mysqli_query($conn, "SELECT id FROM machines WHERE machine_id='$machineId' LIMIT 1");
if ($row = mysqli_fetch_assoc($res)) {
    $id = $row['id'];
}

$qrValue = "https://smartbuddy.co.in/smartqr/qr_redirect.php?machine_id=$machineId";
?>

<!-- QR Styling Library (NO <a> tag ever) -->
<script src="https://unpkg.com/qr-code-styling@1.5.0/lib/qr-code-styling.js"></script>

<style>
#qrcode {
    display:flex;
    justify-content:center;
    margin:25px auto;
}
#qrcode canvas {
    cursor: default;     /* no pointer */
}
</style>

<div class="container-fluid">
<div class="card shadow">
<div class="card-header text-center"><b>QR Code for Machine</b></div>

<div class="card-body text-center">

<div id="qrcode"></div>

<h5 class="text-primary mt-3">
    <b>Machine ID:</b> <?= htmlspecialchars($machineId) ?>
</h5>

<button id="downloadBtn" class="btn btn-success">Download QR</button>
<button class="btn btn-danger" onclick="location.href='list_machinedetails.php'">Back</button>

</div>
</div>
</div>



<script>
/* =====================
   CREATE QR (NO LINK)
===================== */
const qrCode = new QRCodeStyling({
    width: 200,
    height: 200,
    type: "canvas",
    data: "<?= $qrValue ?>",
    dotsOptions: {
        color: "#000",
        type: "square"
    },
    backgroundOptions: {
        color: "#ffffff"
    }
});

qrCode.append(document.getElementById("qrcode"));

/* =====================
   SAVE QR IN DB
===================== */
setTimeout(() => {
    qrCode.getRawData("png").then(blob => {
        const reader = new FileReader();
        reader.onloadend = function () {
            fetch("save_qr.php", {
                method: "POST",
                headers: {"Content-Type":"application/x-www-form-urlencoded"},
                body: "id=<?= (int)$id ?>&qr_image=" + encodeURIComponent(reader.result)
            });
        };
        reader.readAsDataURL(blob);
    });
}, 800);

/* =====================
   DOWNLOAD QR WITH MACHINE ID TEXT
===================== */
document.getElementById("downloadBtn").onclick = async () => {

    // Get QR image as PNG blob
    const blob = await qrCode.getRawData("png");

    const img = new Image();
    img.src = URL.createObjectURL(blob);

    img.onload = function () {

        const qrSize = img.width;
        const extraBottom = 70;

        // Create new canvas
        const canvas = document.createElement("canvas");
        canvas.width = qrSize;
        canvas.height = qrSize + extraBottom;

        const ctx = canvas.getContext("2d");

        // White background
        ctx.fillStyle = "#ffffff";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // Draw QR
        ctx.drawImage(img, 0, 0);

        // Draw machine ID text
        ctx.fillStyle = "#000000";
        ctx.font = "bold 18px Arial";
        ctx.textAlign = "center";
        ctx.textBaseline = "top";
        ctx.fillText(
            "<?= htmlspecialchars($machineId) ?>",
            canvas.width / 2,
            qrSize + 20
        );

        // Download
        const link = document.createElement("a");
        link.href = canvas.toDataURL("image/png");
        link.download = "machine_<?= htmlspecialchars($machineId) ?>_qrcode.png";
        link.click();
    };
};

</script>
<?php include('include/scripts.php');?>
<?php include('include/footer.php');?>
