<?php
// Database connection details
global $conn;
include 'db-connection.php';
if (isset($_POST['qrCode']) && isset($_POST['qrData'])) {
    $qrCodeBase64 = $_POST['qrCode']; // Base64 encoded QR code
    $qrData = json_decode($_POST['qrData'], true); // Decode the JSON data
    $userId = intval($qrData['userId']); // User ID as integer

    // Decode Base64 string to binary
    $qrBinary = base64_decode($qrCodeBase64);

    // Insert QR code into `qr` table
    $stmt = $conn->prepare("INSERT INTO qr (accid, qrCode, qrText) VALUES (?, ?, ?)");

    // Bind parameters for binary and Base64
    $null = NULL; // Use NULL to bind the binary data as a BLOB
    $qrText = json_encode($qrData); // JSON data as a string
    $stmt->bind_param("ibs", $userId, $null, $qrText); // 'b' for Blob, 's' for JSON string

    // Send the actual binary data (QR code image)
    $stmt->send_long_data(1, $qrBinary);

    // Check if the insertion was successful
    if ($stmt->execute()) {
        $qrID = $stmt->insert_id; // Get the `qrID` from the inserted row

        // Insert primary user data into `listqr`
        $primary = $qrData['primary'];
        $listStmt = $conn->prepare("INSERT INTO listqr (Fullname, Age, Sex, qrID, userID) VALUES (?, ?, ?, ?, ?)");
        $listStmt->bind_param("sisii", $primary['fullname'], $primary['age'], $primary['sex'], $qrID, $userId);

        // Execute the query for the primary user
        if (!$listStmt->execute()) {
            echo "Error inserting primary user data into listqr: " . $listStmt->error;
        }

        // Insert evacuees' data into `listqr`
        if (isset($qrData['evacuees']) && is_array($qrData['evacuees'])) {
            foreach ($qrData['evacuees'] as $evacuee) {
                // Prepare the statement for each evacuee
                $listStmt->prepare("INSERT INTO listqr (Fullname, Age, Sex, qrID, userID) VALUES (?, ?, ?, ?, ?)");
                $listStmt->bind_param("sisii", $evacuee['name'], $evacuee['age'], $evacuee['sex'], $qrID, $userId);

                // Execute for each evacuee
                if (!$listStmt->execute()) {
                    echo "Error inserting evacuee data into listqr: " . $listStmt->error;
                }
            }
        }

        // Close the statement
        $listStmt->close();
        echo "QR code (Base64 and Binary) and list data saved successfully!";
    } else {
        echo "Error inserting data into qr table: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Invalid request. Missing QR code or data.";
}

// Close the database connection
$conn->close();
?>
