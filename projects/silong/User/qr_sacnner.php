<?php
global $conn;
header('Content-Type: application/json'); // Ensure JSON response

include 'db-connection.php';
if (isset($_POST['qrData'])) {
    // Get the scanned QR data from the request
    $qrData = $_POST['qrData'];

    // Query to match QR code text in the database
    $stmt = $conn->prepare("SELECT qrID, qrText FROM qr WHERE qrText = ?");
    $stmt->bind_param("s", $qrData); // Use the qrData as the parameter
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($qrID, $qrText);
        $stmt->fetch();

        $response = [
            'success' => true,
            'qrID' => $qrID,
            'qrText' => $qrText,
        ];

        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'No matching QR code found.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request. Missing QR data.']);
}
?>
