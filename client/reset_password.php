<?php
include 'include/config.php';
date_default_timezone_set('Asia/Kolkata');

$message = "";
$alertClass = "";
$token = $_GET['token'] ?? '';
$user = null;

$current_time = date("Y-m-d H:i:s");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Step 1️⃣: Validate token in URL
    if (!$token) {
        $message = "❌ Invalid or missing token.";
        $alertClass = "alert-danger";
    } else {
        $stmt = $conn->prepare("SELECT * FROM clients WHERE reset_token = ? AND reset_expires > ?");
        $stmt->bind_param("ss", $token, $current_time);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            $message = "⚠️ Invalid or expired reset link.";
            $alertClass = "alert-danger";
        }
    }
}

// Step 2️⃣: Handle new password submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $new_pass = $_POST['password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';

    if (strlen($new_pass) < 4) {
        $message = "⚠️ Password must be at least 4 characters.";
        $alertClass = "alert-warning";

        // fetch user to show form again
        $stmt = $conn->prepare("SELECT * FROM clients WHERE reset_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    }
    elseif ($new_pass !== $confirm_pass) {
        $message = "❌ Passwords do not match.";
        $alertClass = "alert-danger";

        // fetch user to show form again
        $stmt = $conn->prepare("SELECT * FROM clients WHERE reset_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
    } else {
        // Verify token again before updating password
        $stmt = $conn->prepare("SELECT * FROM clients WHERE reset_token = ? AND reset_expires > ?");
        $stmt->bind_param("ss", $token, $current_time);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Hash new password
            $hashed = $new_pass;

            // Update password and clear token
            $update = $conn->prepare("UPDATE clients SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $update->bind_param("si", $hashed, $user['id']);
            $update->execute();

            $message = "✅ Password has been successfully reset. You can now <a href='index.php'>login</a>.";
            $alertClass = "alert-success";
        } else {
            $message = "⚠️ Invalid or expired token.";
            $alertClass = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

  <div class="card shadow-lg p-4" style="width: 400px; border-radius: 1rem;">
    <h3 class="text-center mb-3">Reset Password</h3>

    <?php if (!empty($message)): ?>
      <div class="alert <?php echo $alertClass; ?>"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!empty($user) && $alertClass !== "alert-success"): ?>
      <form method="post">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

        <div class="mb-3">
          <label for="password" class="form-label">New Password</label>
          <input type="password" id="password" name="password" class="form-control" required minlength="4">
        </div>

        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" required minlength="4">
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-success">Update Password</button>
        </div>
      </form>
    <?php endif; ?>
  </div>

</body>
</html>
