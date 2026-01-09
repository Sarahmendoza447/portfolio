<?php
global $conn;
include 'db-connection.php';
// Check if the date and EvacID are provided
$selectedDate = isset($_GET['date']) ? $_GET['date'] : null;
$selectedEvacID = isset($_GET['evacID']) ? $_GET['evacID'] : null;

if ($selectedDate) {
    // SQL query to get the requested goods per date and EvacID (if provided)
    $query = "SELECT g.GoodsName, SUM(gr.ReqNum) AS ReqNum
              FROM goodsreq gr
              JOIN goodslist g ON gr.GoodsID = g.GoodsID
              WHERE gr.Date = ?";

    if ($selectedEvacID) {
        $query .= " AND gr.EvacID = ?";
    }

    $query .= " GROUP BY g.GoodsName";

    $stmt = $conn->prepare($query);

    // Bind parameters based on whether EvacID is set
    if ($selectedEvacID) {
        $stmt->bind_param("ss", $selectedDate, $selectedEvacID);
    } else {
        $stmt->bind_param("s", $selectedDate);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch the data
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return the data as JSON
    echo json_encode($data);

    $stmt->close();
    $conn->close();
    exit;
}

// Fetch Evacuation IDs for the filter dropdown
$query = "SELECT DISTINCT EvacID FROM goodsreq";
$result = $conn->query($query);
$evacuationIDs = [];
while ($row = $result->fetch_assoc()) {
    $evacuationIDs[] = $row['EvacID'];
}
?>
