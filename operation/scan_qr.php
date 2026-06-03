<!DOCTYPE html>
<html>
<head>
  <title>Scan & Pay</title>
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Razorpay Checkout -->
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      padding: 20px;
      background: #f9f9f9;
    }
    #reader {
      width: 400px;
      margin: auto;
    }
    #result {
      margin-top: 20px;
      font-size: 18px;
      font-weight: bold;
      color: green;
    }
  </style>
</head>
<body>
  <h2>Scan QR to Pay</h2>
  <div id="reader"></div>
  <div id="result">Scanned Value: <span id="scannedValue">None</span></div>

  <script>
    function onScanSuccess(decodedText, decodedResult) {
      document.getElementById("scannedValue").innerText = decodedText;

      // Example QR text:
      // "Machine ID: Al2324SO0001_E2T37\nAmount: 2"
      let lines = decodedText.split(/\r?\n/);
      let machineId = null;
      let amount = null;

      lines.forEach(line => {
        if (line.toLowerCase().includes("machine id")) {
          machineId = line.split(":")[1].trim();
        }
        if (line.toLowerCase().includes("amount")) {
          amount = line.split(":")[1].trim();
        }
      });

      if(!machineId || !amount){
        alert("❌ Invalid QR code format. Expected 'Machine ID: ...' and 'Amount: ...'");
        return;
      }

      // 🔹 Call payorder.php to create Razorpay order
      $.post("payorder.php", { machine_id: machineId, amount: amount }, function(orderData) {
        if(orderData.error){
            alert(orderData.error);
            return;
        }

        let options = {
            "key": orderData.key,
            "amount": orderData.amount,   // in paise from backend
            "currency": "INR",
            "name": "Machine Payment",
            "description": "Payment for Machine " + machineId,
            "order_id": orderData.order_id,
            "handler": function (response){
                alert(
                    "✅ Payment Successful!\n" +
                    "Machine ID: " + machineId + "\n" +
                    "Amount: ₹" + amount + "\n" +
                    "Payment ID: " + response.razorpay_payment_id
                );
            },
            "theme": { "color": "#3399cc" },
            "method": { "wallet": "phonepe" },
            "prefill": {
                "method": "wallet",
                "wallet": "phonepe"
            }
        };

        let rzp1 = new Razorpay(options);
        rzp1.open();
      }, "json");

      // Stop scanner after successful scan
      html5QrcodeScanner.clear();
    }

    function onScanError(errorMessage) {
      // ignore errors
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess, onScanError);
  </script>
</body>
</html>
