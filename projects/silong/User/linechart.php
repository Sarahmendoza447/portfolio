<?php 
include 'db-connection.php';
// Fetch all EvacIDs for the filter dropdown
$evacQuery = "SELECT DISTINCT EvacID FROM goodsreq";
$evacResult = $conn->query($evacQuery);

// Automatically fetch data for the last 7 days
$evID = isset($_GET['EvacID']) ? $_GET['EvacID'] : null;
$query = "SELECT g.GoodsName, gr.Date, SUM(gr.ReqNum) AS ReqNum
          FROM goodsreq gr
          JOIN goodslist g ON gr.GoodsID = g.GoodsID
          WHERE gr.Date BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE()";

// Add EvacID condition if provided
if ($evID) {
    $query .= " AND gr.EvacID = ?";
}

// Grouping and ordering the data
$query .= " GROUP BY g.GoodsName, gr.Date
            ORDER BY gr.Date ASC";

// Prepare and execute the query
$stmt = $conn->prepare($query);

if ($evID) {
    $stmt->bind_param("i", $evID); // Assuming EvacID is an integer
}

$stmt->execute();
$result = $stmt->get_result();

// Fetch the data
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return the data as JSON
$stmt->close();
$conn->close();
?>
