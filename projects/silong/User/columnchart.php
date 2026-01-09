<?php
include 'db-connection.php';
// Query to fetch data
$sql = "SELECT evac_name, evacuees, evac_capacity FROM evacuation_areas";
$result = $conn->query($sql);

// Fetch the data and prepare it for JavaScript
$evac_names = [];
$evacuees = [];
$evac_capacity = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $evac_names[] = $row["evac_name"];
        $evacuees[] = $row["evacuees"];
        $evac_capacity[] = $row["evac_capacity"];
    }
}

$conn->close();
?>
