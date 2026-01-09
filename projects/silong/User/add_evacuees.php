<?php
// Set the response type to JSON
global $conn;
header('Content-Type: application/json');

include '../db-connection.php';

// Check if the accid is passed as a parameter
$accid = isset($_GET['accid']) ? $_GET['accid'] : null;

// If accid is provided, fetch data from the database
if ($accid) {
    // Prepare the SQL query to fetch the required fields
    $sql = "SELECT id, FirstName, MI, LastName, Age, Sex FROM family WHERE accid = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the accid parameter to the query
        $stmt->bind_param("i", $accid); // 'i' denotes integer data type

        // Execute the query
        $stmt->execute();

        // Get the result of the query
        $result = $stmt->get_result();

        // Create an array to store the fetched data
        $data = [];

        // Fetch all rows and store them in the $data array
        while ($row = $result->fetch_assoc()) {
            // Combine FirstName, MI, and LastName into a full name
            $full_name = trim($row['FirstName'] . ' ' . $row['MI'] . ' ' . $row['LastName']);
            // Add the full name, age, sex, and id to the data array
            $data[] = [
                'id' => $row['id'],
                'name' => $full_name,
                'age' => $row['Age'],
                'sex' => $row['Sex']
            ];
        }

        // Output the data as a JSON response
        echo json_encode($data);
    } else {
        echo json_encode(["error" => "Failed to prepare the database query"]);
    }
} else {
    // If no accid is provided, send an error message
    echo json_encode(["error" => "accid parameter is missing"]);
}

// Close the database connection
$conn->close();
?>
