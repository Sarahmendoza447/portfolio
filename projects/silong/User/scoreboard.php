<?php 
include 'db-connection.php';
// 1. Fetch total number of residents from family table
$residentsQuery = "SELECT COUNT(id) AS totalResidents FROM family";
$residentsResult = $conn->query($residentsQuery);
$residentsData = $residentsResult->fetch_assoc();
$totalResidents = $residentsData['totalResidents'];

// 2. Fetch total number of users from user table
$usersQuery = "SELECT COUNT(ID) AS totalUsers FROM `user`";
$usersResult = $conn->query($usersQuery);
$usersData = $usersResult->fetch_assoc();
$totalUsers = $usersData['totalUsers'];

// Calculate total residents by summing family and user counts
$totalResidents = $totalResidents + $totalUsers;

// 3. Fetch total number of evacuees (assuming it's stored in the evacuation_areas table)
$evacQuery = "SELECT SUM(evacuees) AS totalEvacuees FROM evacuation_areas";
$evacResult = $conn->query($evacQuery);
$evacData = $evacResult->fetch_assoc();
$totalEvacuees = $evacData['totalEvacuees'];

// 4. Fetch total donations (assuming it's stored in the Donation table)
$donationQuery = "SELECT SUM(`Total_Donations`) AS totalDonation FROM donation";
$donationResult = $conn->query($donationQuery);
$donationData = $donationResult->fetch_assoc();
$totalDonation = $donationData['totalDonation'];

// Close the connection
$conn->close();

?>