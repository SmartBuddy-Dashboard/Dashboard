<?php

error_reporting(E_ALL); // Report all errors
ini_set('display_errors', 1); // Show errors on the page
ini_set('display_startup_errors', 1);

include 'include/config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$message = "";
$alertClass = "";
date_default_timezone_set('Asia/Kolkata');

$base_url = "https://smartbuddy.co.in/smartqr/client/"; // adjust if different

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare("SELECT * FROM clients WHERE contact_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", strtotime("+10 minutes"));

            $update = $conn->prepare("UPDATE clients SET reset_token = ?, reset_expires = ? WHERE contact_email = ?");
            $update->bind_param("sss", $token, $expires, $email);
            $update->execute();

            $resetLink = $base_url . "/reset_password.php?token=" . urlencode($token);

            // Send email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'trifrnd.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'software@trifrnd.com'; // 🔹 your Gmail
                $mail->Password = 'Trifrnd@2025';   // 🔹 your Gmail App Password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('software@trifrnd.com', 'TRIFRND SOFTWARE');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "
                    <p>Hello,</p>
                    <p>Click the link below to reset your password:</p>
                    <p><a href='$resetLink'>$resetLink</a></p>
                    <p><b>Note:</b> This link will expire in 10 min.</p>
                ";

                $mail->send();
                $message = "✅ Password reset link has been sent to your email! This link will expire in 10 min.";
                $alertClass = "alert-success";

            } catch (Exception $e) {
                $message = "❌ Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
                $alertClass = "alert-danger";
            }
        } else {
            $message = "❌ Email not found in our records!";
            $alertClass = "alert-danger";
        }

        $stmt->close();
    } else {
        $message = "⚠️ Please enter a valid email address.";
        $alertClass = "alert-warning";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow-lg p-4" style="width: 400px; border-radius: 1rem;">
    <h3 class="text-center mb-3">Forgot Password</h3>

    <?php if (!empty($message)): ?>
      <div class="alert <?php echo $alertClass; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Enter your email address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="example@gmail.com" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Send Reset Link</button>
      </div>
    </form>

    <div class="text-center mt-3">
      <a href="index.php" class="text-decoration-none">Back to Login</a>
    </div>
  </div>

</body>
</html>
