<?php
// Database connection
global $conn;
include 'db-connection.php';
// SQL query to fetch expenses
$sql = "SELECT ExpensesID, description, Amount, Receipt FROM expenses"; // Adjust the table name and fields as needed
$result = $conn->query($sql);

$expenses = [];

if ($result->num_rows > 0) {
    // Fetch each row and store it in the array
    while($row = $result->fetch_assoc()) {
        // Convert the LONGBLOB data (receipt) into a base64-encoded string
        $receiptBase64 = base64_encode($row['Receipt']);
        $row['Receipt'] = 'data:image/jpeg;base64,' . $receiptBase64; // Assuming the image is JPEG
        $expenses[] = $row;
    }
}

// Convert the PHP array to JSON and return it
echo json_encode($expenses);

$conn->close();
?>
