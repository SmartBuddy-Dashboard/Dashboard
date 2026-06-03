<?php
include_once('include/config.php');

if (isset($_POST['id']) && isset($_POST['qr_image'])) {
    $id = intval($_POST['id']);
    $qrImage = $_POST['qr_image'];

    // Remove "data:image/png;base64,"
    $qrImage = str_replace('data:image/png;base64,', '', $qrImage);
    $qrImage = str_replace(' ', '+', $qrImage);

    $qrBlob = base64_decode($qrImage);

    // Check if qr_code is already set
    $checkStmt = $conn->prepare("SELECT qr_code FROM machines WHERE id = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkStmt->store_result();

    $qrExists = false;
    if ($checkStmt->num_rows > 0) {
        $checkStmt->bind_result($existingQR);
        $checkStmt->fetch();
        if (!empty($existingQR)) {
            $qrExists = true;
        }
    }
    $checkStmt->close();

    if ($qrExists) {
        echo "QR already exists. Not updated.";
    } else {
        $stmt = $conn->prepare("UPDATE machines SET qr_code = ? WHERE id = ?");
        $stmt->bind_param("bi", $qrBlob, $id);

        // workaround for blob
        $null = NULL;
        $stmt->send_long_data(0, $qrBlob);

        if ($stmt->execute()) {
            echo "QR saved successfully.";
        } else {
            echo "Error saving QR.";
        }
        $stmt->close();
    }
}
?>
