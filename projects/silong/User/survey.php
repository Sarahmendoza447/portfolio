<?php
// Start output buffering to prevent any unwanted output
ob_start();

global $conn, $evacID;
session_start();
include 'db-connection.php';

$accID = 1; // Or use a specific account ID if available
$evacID; // Initialize EvacID

// Get EvacID from evacuees table based on the current user's AccID
$sql_get_evacID = "SELECT evacuation_id FROM evacuees WHERE AccID = '$accID' LIMIT 1";
$result_evac = $conn->query($sql_get_evacID);
if ($result_evac->num_rows > 0) {
    $evacID = $result_evac->fetch_assoc()['evacuation_id'];
} else {
    echo "Error: EvacID not found for user.";
    exit;
}

// Calculate total number of people by counting distinct AccID for the current EvacID
$sql_total_num_people = "SELECT COUNT(DISTINCT AccID) AS totalNumPeople FROM evacuees WHERE evacuation_id = '$evacID'";
$result_total_people = $conn->query($sql_total_num_people);
$totalnumpeople = $result_total_people->num_rows > 0 ? $result_total_people->fetch_assoc()['totalNumPeople'] : 0;

// Fetch goods and their vote counts for today
$sql_goods = "SELECT GoodsID, GoodsName FROM goodslist";
$result_goods = $conn->query($sql_goods);

$goods = [];
if ($result_goods->num_rows > 0) {
    while ($row = $result_goods->fetch_assoc()) {
        $goodsID = $row['GoodsID'];
        $today = date('Y-m-d');
        $sql_req = "SELECT SUM(ReqNum) as totalReqNum FROM goodsreq WHERE GoodsID = '$goodsID' AND EvacID = '$evacID' AND Date = '$today'";
        $result_req = $conn->query($sql_req);
        $reqNum = $result_req->num_rows > 0 ? $result_req->fetch_assoc()['totalReqNum'] : 0;

        $row['ReqNum'] = $reqNum ?: 0;
        $goods[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['goodsID'])) {
    header('Content-Type: application/json'); // Set JSON response header
    $goodsID = $_POST['goodsID'];
    $today = date('Y-m-d');
    $userID = session_id(); // User tracking via session ID

    // Check if the user has already voted for this item today
    $sql_check = "SELECT COUNT(*) as voteCount FROM goodsreq WHERE GoodsID = '$goodsID' AND EvacID = '$evacID' AND Date = '$today' AND UserID = '$userID'";
    $result_check = $conn->query($sql_check);
    $hasVoted = $result_check->fetch_assoc()['voteCount'] > 0;

    if (!$hasVoted) {
        // Insert a new record for the vote
        $sql_insert = "INSERT INTO goodsreq (UserID, GoodsID, EvacID, ReqNum, Date) VALUES ('$userID', '$goodsID', '$evacID', 1, '$today')";
        $conn->query($sql_insert);

        echo json_encode(['success' => true, 'message' => 'Vote recorded successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'You can only vote once per day for this item.']);
    }
    exit;
}

// End output buffering and clean
ob_end_flush();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relief Survey</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        #goods-list {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Goods Container */
        .goods-container {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            background: linear-gradient(90deg, red, white); /* Gradient transition */
            background-size: 200% 200%;
            animation: gradient-animation 5s infinite alternate; /* Smooth animation */
        }

        /* Keyframes for Background Gradient Animation */
        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }

        /* Progress Bar Container */
        .progress-bar-container {
            flex-grow: 1;
            margin-left: 15px;
            height: 20px;
            background-color: #f3f3f3;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        /* Progress Bar */
        .progress-bar {
            height: 100%;
            text-align: center;
            color: white;
            font-size: 0.85em;
            line-height: 20px;
            white-space: nowrap;
            position: relative;
            overflow: hidden;
            background-color: var( #2196f3); /* Keep original bar color */
            transition: width 0.5s ease-in-out;
        }

        /* Liquid Wave Effect */
        .progress-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 200%;
            background: rgba(255, 255, 255, 0.3); /* Slightly lighter shade for the wave effect */
            opacity: 0.8;
            border-radius: 20px; /* Match the progress bar's shape */
            transform: translateX(-100%);
            animation: liquid-wave 3s infinite linear; /* Liquid animation */
        }

        /* Label Style (Food, Water, etc.) */
        .label {
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Clean and modern font */
            font-weight: 600; /* Bold text for better visibility */
            font-size: 1em; /* Adjusted font size for better balance */
            color: #000; /* Black text for better contrast */
            letter-spacing: 0.5px; /* Slight spacing for a more refined look */
            z-index: 2;
        }


        /* Button Style */
        .add-button {
            background-color: transparent;
            border: none;
            color: #2196f3;
            font-size: 1.5em;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .add-button:hover {
            color: #1769aa;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            #goods-list {
                padding: 10px;
            }

            .goods-container {
                flex-direction: column; /* Stack items vertically */
                align-items: stretch;
            }

            .progress-bar-container {
                margin-left: 0;
                margin-top: 10px; /* Add space between the progress bar and button */
                width: 100%; /* Ensure the progress bar takes full width */
            }

            .add-button {
                align-self: flex-start; /* Keep button on top in smaller screens */
                margin-bottom: 10px;
            }

            .label {
                font-size: 0.75em; /* Adjust font size for smaller screens */
                left: 5px;
            }
        }

        @media screen and (max-width: 768px) {
            .label {
                font-size: 0.85em; /* Slightly smaller on smaller screens */
                left: 5px; /* Adjust position for smaller screens */
            }
        }
        /* Button Style */

        .add-button {
            background-color: white;
            border: none;
            color: black; /* Black icon for contrast */
            font-size: 1.5em;
            cursor: pointer;
            padding: 5px;
            transition: transform 0.3s ease; /* Keep zoom effect */
            border-radius: 5px;
        }

        .add-button:hover {
            transform: scale(1.1);
            color: #D72323;
        }
        h1 {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-right: 10px;
        }


        /* Keyframes for Liquid Wave Animation */
        @keyframes liquid-wave {
            0% {
                transform: translateX(-100%);
            }
            50% {
                transform: translateX(0%);
            }
            100% {
                transform: translateX(100%);
            }
        }

    </style>
    <!-- Include Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
<h1>
    <img src="images/reliefsurvey.png" alt="Logo" class="logo"> Relief Survey
</h1>

<div id="goods-list">
    <?php
    // Define colors
    $colors = ['#4caf50', '#2196f3', '#ff9800', '#f44336', '#9c27b0', '#00bcd4'];
    $colorIndex = 0;


    foreach ($goods as $good):
        $percentage = ($good['ReqNum'] / $totalnumpeople) * 100;
        $color = $colors[$colorIndex % count($colors)];

        $colorIndex++;
        ?>
        <div class="goods-container">
            <!-- Add Button with Plus Icon -->
            <button class="add-button" onclick="vote(<?php echo $good['GoodsID']; ?>)">
                <i class="fas fa-plus"></i>
            </button>
            <div class="progress-bar-container">
                <!-- Label Positioned Over the Progress Bar -->
                <div class="label">
                    <?php echo htmlspecialchars($good['GoodsName']); ?> (<?php echo round($percentage, 2); ?>%)
                </div>
                <div id="progress-bar-<?php echo $good['GoodsID']; ?>"
                     class="progress-bar"
                     style="width: <?php echo $percentage; ?>%; background-color: <?php echo $color; ?>;">
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function vote(goodsID) {
        fetch('survey.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'goodsID=' + encodeURIComponent(goodsID) // Correctly encode the goodsID
        })
            .then(response => {
                if (!response.ok) throw new Error('Server error: ' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Success alert with SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Vote Successful!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Refresh to update progress
                    });
                } else {
                    // Error alert with SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,
                        confirmButtonText: 'Try Again'
                    });
                }
            })
            .catch(error => console.error('Fetch error:', error));
    }



</script>
</body>
</html>
