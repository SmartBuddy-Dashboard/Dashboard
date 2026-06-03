<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Amount Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body class="p-4">

  <h1>Machine Payment List</h1>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Machine ID</th>
        <th>Amount (₹)</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      include 'include/config.php';

      $sql = "SELECT machine_id, uses_amt FROM machines";
      $result = $conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['machine_id']}</td>
                  <td>{$row['uses_amt']}</td>
                  <td>
  <button class='btn btn-success pay-btn'
          data-machineid='{$row['machine_id']}'
          data-amount='{$row['uses_amt']}'>
    Pay
  </button>
</td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='3' class='text-center'>No Machines Found</td></tr>";
      }
      ?>
    </tbody>
  </table>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).on("click", ".pay-btn", function() {
    let machineId = $(this).data("machineid");
    let amount = $(this).data("amount");

    // Call payorder.php directly
    $.post("payorder.php", { machine_id: machineId, amount: amount }, function(orderData) {
        if(orderData.error){
            alert(orderData.error);
            return;
        }

        let options = {
            "key": orderData.key,
            "amount": orderData.amount,
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
            "theme": {
                "color": "#3399cc"
            },
            // 🔹 Default payment selection
    "method": {
        "wallet": "phonepe"   // Force wallet selection
    },
    "prefill": {
        "method": "wallet",   // Default method
        "wallet": "phonepe"   // Default wallet
    }
        };

        let rzp1 = new Razorpay(options);
        rzp1.open();
    }, "json");
});
</script>

</body>
</html>