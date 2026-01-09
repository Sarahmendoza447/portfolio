<?php
global $conn;
header('Content-Type: application/json');

include 'db-connection.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch evacuation center data
$sql = "SELECT evac_name, evac_lat, evac_lng, evac_capacity, evacuees, picture FROM evacuation_areas";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Convert BLOB to base64 string
        $photoBase64 = base64_encode($row['picture']); // Assuming 'picture' is the correct column name

        $data[] = [
            'evac_name' => $row['evac_name'],
            'evac_lat' => floatval($row['evac_lat']),
            'evac_lng' => floatval($row['evac_lng']),
            'evac_capacity' => intval($row['evac_capacity']),
            'evacuees' => intval($row['evacuees']),
            'photo_base64' => $photoBase64
        ];
    }
}

$conn->close();
echo json_encode($data);
?>
