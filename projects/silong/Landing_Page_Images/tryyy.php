<?php
session_start();

// Check if the user is signed in
if (isset($_SESSION['accid'])) {
    $user_id = $_SESSION['accid'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "silong"; // Replace with your database name

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to fetch user data including the profile picture
    $sql = "SELECT First_name, picture FROM user WHERE accid = $user_id";
    $result = $conn->query($sql);


    // Check if a user record exists
    if ($result->num_rows > 0) {
        // Fetch the data
        $user_data = $result->fetch_assoc();
        $full_name = $user_data['First_name'] ;
        $profile_picture_blob = $user_data['picture']; // Binary data of the image

        // Encode the binary data to base64 for use in an <img> tag
        $profile_picture = 'data:image/jpeg;base64,' . base64_encode($profile_picture_blob);
    } else {
        echo "0 results";


    }


    // Close the connection
    $conn->close();
} else {
    // Redirect to login if the user is not signed in
    header("Location: login_page.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILONG Mobile Interface</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .modal-header.red-header {
            background-color: #dc3545;
            color: #fff;
        }
        .modal-content.red-border {
            border: 2px solid #dc3545;
        }
        .btn-red {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }
        .btn-red:hover {
            background-color: #b52e3d;
        }
        .modal-body,
        .modal-footer,
        .modal-title {
            color: #000; /* Set the text color to black */
        }
        .form-label, .form-check-label {
            color: #000; /* Ensure form labels are also black */
        }
        .prof-img {
            width: 50px; /* Adjust as needed */
            height: 50px; /* Ensure the image is square */
            border-radius: 50%; /* Makes the image circular */
            object-fit: cover; /* Ensures the image fits within the circle */
            border: 2px solid #ccc; /* Optional: Adds a border */
        }

    </style>
</head>
<body>
<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">
        <img src="../Landing_Page_Images/silong.png" alt="SILONG Logo" class="logo-img">
    </div>
    <div class="nav-icons">
        <a href="#" class="nav-item"><img src="images/notification-icon.png" alt="Notification" class="icon-img"></a>
        <a href="#" class="nav-item"><img src="<?php echo $profile_picture; ?>" alt="User" class="prof-img"></a>
    </div>
</div>

<!-- Tabs -->
<div class="tabs">
    <div class="tab-item active" data-target="rescue">
        <img src="images/rescue-icon.png" alt="Rescue Icon" class="tab-icon">
        <span>Rescue</span>
    </div>
    <div class="tab-item" data-target="evacuate">
        <img src="images/evacuate-icon.png" alt="Evacuate Icon" class="tab-icon">
        <span>Evacuate</span>
    </div>
    <div class="tab-item" data-target="relief">
        <img src="images/relief-icon.png" alt="Relief Goods Icon" class="tab-icon">
        <span>Relief Goods</span>
    </div>
    <div class="tab-item" data-target="donation">
        <img src="images/donation-icon.png" alt="Donation Icon" class="tab-icon">
        <span>Donation</span>
    </div>
</div>

<!-- Scrollable Container -->
<div class="scrollable-container">
    <!-- First Container: Rescue -->
    <div class="content-container" id="rescue">
        <div class="rescue-card card">
            <div class="card-header">
                <img src="images/rescue-icon.png" alt="Rescue Icon" class="header-icon">
                <span>RESCUE</span>
            </div>
            <!-- Updated Button -->
            <button class="btn btn-red" data-bs-toggle="modal" data-bs-target="#rescueModal">SEND RESCUE</button>
            <p>Emergency Hotline</p>
            <a href="tel:+639175089911" class="hotline">+63 917 508 9911</a>
        </div>
    </div>

    <!-- Second Container: Evacuate -->
    <div class="content-container" id="evacuate">
        <div class="evacuate-card card">
            <div class="card-header">
                <img src="images/evacuate-icon.png" alt="Evacuate Icon" class="header-icon">
                <span>EVACUATE</span>
            </div>
            <button class="btn btn-red">EVACUATE NOW!</button>
            <button class="btn btn-yellow">View Evacuation Area</button>
        </div>
    </div>

    <!-- Third Container: Relief Goods -->
    <div class="content-container" id="relief">
        <div class="relief-card card">
            <div class="card-header">
                <img src="images/relief-icon.png" alt="Relief Icon" class="header-icon">
                <span>RELIEF GOODS</span>
            </div>
            <button class="btn btn-yellow">TRACKER</button>
            <button class="btn btn-red">SURVEY</button>
        </div>
    </div>

    <!-- Fourth Container: Donation -->
    <div class="content-container" id="donation">
        <div class="donation-card card">
            <div class="card-header">
                <img src="images/donation-icon.png" alt="Donation Icon" class="header-icon">
                <span>DONATION</span>
            </div>
            <button class="btn btn-yellow">SEND DONATION</button>
            <button class="btn btn-red">TRACK DONATION</button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rescueModal" tabindex="-1" aria-labelledby="rescueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content red-border">
            <div class="modal-header red-header">
                <h5 class="modal-title" id="rescueModalLabel">Rescue Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>We will send an immediate rescue. Please provide additional information:</p>
                <form id="rescueForm">
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity of People</label>
                        <input type="number" id="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="childrenCheck">
                        <label class="form-check-label" for="childrenCheck">Are there children?</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="elderlyCheck">
                        <label class="form-check-label" for="elderlyCheck">Are there elderly?</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmRescue()">OK</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
<script>
    // Function to confirm rescue and process the data
    function confirmRescue() {
        const quantity = document.getElementById("quantity").value;
        const isChildren = document.getElementById("childrenCheck").checked ? 1 : 0;
        const isElderly = document.getElementById("elderlyCheck").checked ? 1 : 0;

        // Check if the quantity is valid
        if (quantity < 1) {
            alert("Please enter a valid number of people.");
            return;
        }

        // Get user's location before submitting
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Use the latitude and longitude to get the address
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${latitude}&lon=${longitude}&format=json&addressdetails=1`)
                    .then(response => response.json())
                    .then(data => {
                        const address = data.display_name; // Get the address from the response
                        sendRescueDataToServer(quantity, isChildren, isElderly, latitude, longitude, address);
                    })
                    .catch(error => {
                        console.error('Error fetching address:', error);
                        alert("There was an issue fetching the address. Please try again.");
                    });
            }, function(error) {
                alert("Unable to retrieve your location. Please ensure location services are enabled.");
            });
        } else {
            alert("Geolocation is not supported by your browser.");
        }
    }

    // Function to send the data to the server
    function sendRescueDataToServer(quantity, isChildren, isElderly, latitude, longitude, address) {
        const accid = <?php echo $user_id; ?>; // Get the user ID from PHP

        // Send the data via AJAX to insert into the database
        fetch("insert_rescue.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                accid: accid,
                quantity: quantity,
                is_children: isChildren,
                is_elderly: isElderly,
                latitude: latitude,
                longitude: longitude,
                address: address,
                status: "waiting" // Set status as "waiting" or any other status as needed
            })
        })
            .then(response => response.json())
            .then(data => {
                console.log('Data inserted successfully:', data);
                alert("Rescue request sent successfully!");
                // Close the modal after success
                $('#rescueModal').modal('hide');
            })
            .catch(error => {
                console.error('Error sending data:', error);
                alert("There was an issue sending your rescue request. Please try again.");
            });
    }

</script>

</body>
</html>
