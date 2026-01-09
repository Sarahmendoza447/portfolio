<?php
// Fetch evacuation areas from the database
function fetchEvacuationAreas() {
    // Database credentials
    $host = "sql202.infinityfree.com";
    $dbname = "if0_37555773_Silong";
    $username = "if0_37555773";
    $password = "xgQBNINXZ5Y7H";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to fetch evacuation areas
        $stmt = $pdo->query("SELECT id, evac_name, evac_lat, evac_lng, evacuees, evac_capacity FROM evacuation_areas");
        $areas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return json_encode($areas); // Return data as JSON
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return json_encode([]);
    }
}

// Output evacuation areas as JSON for JavaScript
header('Content-Type: application/json');
echo fetchEvacuationAreas();
?>
