<?php
global $conn;
include 'db-connection.php';

$sql = "SELECT id, evac_name, evac_lat, evac_lng, evacuees, evac_capacity, picture FROM evacuation_areas";
$result = $conn->query($sql);

$evacuation_areas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['picture'] = base64_encode($row['picture']); // Encode the binary data
        $evacuation_areas[] = $row;
    }
}
$conn->close();

echo json_encode($evacuation_areas);
?>
