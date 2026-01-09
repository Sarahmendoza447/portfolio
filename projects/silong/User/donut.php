<?php
include 'db-connection.php';
// Get distinct EID for dropdown
$sqlEID = "SELECT DISTINCT evacuation_id AS EID FROM evacuees"; // Using EvacID as EID in SQL
$EIDs = $conn->query($sqlEID);

// Query the database for evacuees based on selected EID
$EIDFilter = isset($_GET['EID']) ? $_GET['EID'] : ''; // Get selected EID
$sql = "SELECT Age, Sex, evacuation_id AS EID 
        FROM evacuees
        WHERE evacuation_id = '$EIDFilter' OR '$EIDFilter' = ''"; // Include selected EID or all if none selected
$result = $conn->query($sql);

$data = [
    "male" => [0, 0, 0, 0], // Kids, Teenagers, Adults, Seniors
    "female" => [0, 0, 0, 0],
];

while ($row = $result->fetch_assoc()) {
    $age = $row['Age'];
    $sex = strtolower($row['Sex']);
    $EID = $row['EID'];

    // Categorize age groups
    if ($age <= 12) $index = 0; // Kids
    elseif ($age <= 19) $index = 1; // Teenagers
    elseif ($age <= 59) $index = 2; // Adults
    else $index = 3; // Seniors

    // Add to respective gender if selected EID matches or if all EIDs are selected
    if ($sex == 'male') {
        $data["male"][$index]++;
    } elseif ($sex == 'female') {
        $data["female"][$index]++;
    }
}

$conn->close();

// Convert PHP data to JSON for JavaScript
$jsonData = json_encode($data);
?>
