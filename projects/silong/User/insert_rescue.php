<?php

global $conn;
include 'db-connection.php';

// Get the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Extract the values
$accid = $data['accid'];
$latitude = $data['latitude'];
$longitude = $data['longitude'];
$address = $data['address'];
$is_elderly = $data['is_elderly'];
$is_children = $data['is_children'];
$status = $data['status'];
$quantity = $data['quantity'];

// Check if accid already exists
$sql_check = "SELECT accid FROM rescue WHERE accid = '$accid'";
$result = $conn->query($sql_check);

if ($result->num_rows > 0) {
    // Update the existing row
    $sql_update = "UPDATE rescue 
                   SET lat = '$latitude', 
                       longitude = '$longitude', 
                       address = '$address', 
                       is_elderly = '$is_elderly', 
                       is_children = '$is_children', 
                       status = '$status', 
                       quantity = '$quantity' 
                   WHERE accid = '$accid'";

    if ($conn->query($sql_update) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Rescue request updated successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error updating rescue request: " . $conn->error]);
    }
} else {
    // Insert a new row
    $sql_insert = "INSERT INTO rescue (accid, lat, longitude, address, is_elderly, is_children, status, quantity) 
                   VALUES ('$accid', '$latitude', '$longitude', '$address', '$is_elderly', '$is_children', '$status', '$quantity')";

    if ($conn->query($sql_insert) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Rescue request inserted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error inserting rescue request: " . $conn->error]);
    }
}

$conn->close();
?>
