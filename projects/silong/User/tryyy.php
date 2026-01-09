<?php
global $conn;
session_start();

// Check if the user is signed in
if (isset($_SESSION['accid'])) {
    $user_id = $_SESSION['accid'];

    include 'db-connection.php';
    // Prepare the SQL query
    $stmt = $conn->prepare("SELECT First_name, Middle_name, Last_name, picture, age, sex FROM user WHERE accid = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user record exists
    if ($result->num_rows > 0) {
        // Fetch the data
        $user_data = $result->fetch_assoc();
        $first_name = htmlspecialchars($user_data['First_name']);
        $middle_name = htmlspecialchars($user_data['Middle_name'] ?? '');
        $last_name = htmlspecialchars($user_data['Last_name']);
        $age = htmlspecialchars($user_data['age']);
        $sex = htmlspecialchars($user_data['sex']);
        $profile_picture_blob = $user_data['picture'];

        // Concatenate to form the full name
        $full_name = $first_name . ' ' . $middle_name . ' ' . $last_name;

        // Encode the binary data to base64 for use in an <img> tag
        $profile_picture = $profile_picture_blob
            ? 'data:image/jpeg;base64,' . base64_encode($profile_picture_blob)
            : ''; // Handle missing picture
    } else {
        echo "No user data found.";
    }

    // Close the connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect to login if the user is not signed in
    header("Location: ../log.php");
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
        #modal1  {
            display: none;  /* Hide modal by default */
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        #modal1 .modal-content {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            max-height: 90%;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #modal1 .modal-header {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        #modal1 .data-table {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            width: 100%;
            border-collapse: collapse;
        }

        #modal1 .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        #modal1 .data-table th {
            background-color: #007bff;
            color: white;
        }

        #modal1 .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        #modal1 .modal-footer button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #modal1 .modal-footer .next {
            background-color: #28a745;
            color: white;
        }

        #modal1 .modal-footer .back {
            background-color: #dc3545;
            color: white;
        }

        #modal1 .total-output {
            font-size: 16px;
            font-weight: bold;
        }
        /* Apply black font color to all text in the modal */
        #modal1 .modal-content {
            color: black;  /* Set the font color to black */
        }

        /* If you want to specifically set the font color for headings, buttons, or other elements: */
        #modal1 .modal-header {
            color: black;  /* Header font color */
        }

        #modal1 .data-table th,
        #modal1 .data-table td {
            color: black;  /* Table text color */
        }

        #modal1 .modal-footer button {
            color: black;  /* Button text color */
        }

        #modal1 .total-output {
            color: black;  /* Total evacuees font color */
        }
        #map { height: 500px; width: 100%; }
        #results { margin-top: 20px; }
        #qrModal {
            display: none; /* Hidden by default */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Centers the modal */
            justify-content: center;
            align-items: center;
            border-radius: 8px; /* Rounded corners */

            max-width: 90%; /* Make sure the modal is responsive */
            width: 500px; /* Default width */

            transition: all 0.3s ease; /* Smooth transition */
        }

        #qrModal.show {
            display: flex; /* Show when the modal has the 'show' class */
        }


        #qrModal .modal-content {
            text-align: center;
            max-width: 400px; /* Set a max-width to avoid stretching */
        }

        canvas {
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        #downloadQR button {
            margin-top: 10px;
        }
        .qr-centered {
            display: block;
            margin: 0 auto; /* Horizontally center the image */
            max-width: 100%; /* Responsive scaling */
        }
        #map-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        #map-content {
            position: relative;
            margin: 10% auto;
            padding: 20px;
            width: 90%;
            max-width: 600px;
            background: #fff;
            border-radius: 10px;
        }
        #close-map-container {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
        }
        #details-container {
            display: none; /* Hidden by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            z-index: 1000; /* Ensure modal is on top */
        }

        #details-content {
            position: relative;
            margin: 10% auto; /* Center the modal vertically */
            padding: 20px;
            width: 90%;
            max-width: 400px;
            background-color: rgba(0, 0, 0, 0.5); /* Make sure the modal itself has a solid background */
            border-radius: 10px;
        }

        #close-details-container {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
        }
        #details-content img {
            max-width: 80% /* Responsive image */
            height: auto; /* Maintain aspect ratio */
            margin-top: 10px;
            display: block;
            margin-left: auto;  /* Horizontally center the image */
            margin-right: auto; /* Horizontally center the image */
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
            <button class="btn btn-red" id="evac_now">EVACUATE NOW!</button>
            <button class="btn btn-yellow" onclick="showevacModal()">Evacuation Centers</button>
        </div>
    </div>

    <!-- Third Container: Relief Goods -->
    <div class="content-container" id="relief">
        <div class="relief-card card">
            <div class="card-header">
                <img src="images/relief-icon.png" alt="Relief Icon" class="header-icon">
                <span>RELIEF GOODS</span>
            </div>
            <button class="btn btn-red" onclick="window.location.href='view_evac.php';">SURVEY</button>

        </div>
    </div>

    <!-- Fourth Container: Donation -->
    <div class="content-container" id="donation">
        <div class="donation-card card">
            <div class="card-header">
                <img src="images/donation-icon.png" alt="Donation Icon" class="header-icon">
                <span>DONATION</span>
            </div>
            <button class="btn btn-red" onclick="window.location.href='donation_user.php';">TRACK DONATION</button>
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
<div class="modal" id="modal1">
    <div class="modal-content">
        <div class="modal-header">Evacuation</div>

        <!-- Top Table (Fetched Data) -->
        <table class="data-table" id="fetchedTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <!-- Data fetched dynamically -->
            </tbody>
        </table>
        <!-- Evacuees List -->
        <table class="data-table" id="evacueesTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Sex</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <!-- Dynamically added rows -->
            </tbody>
        </table>
        <!-- Total Output -->
        <div class="total-output">
            Total Evacuees: <span id="totalCount">0</span>
        </div>

        <!-- Footer Buttons -->
        <div class="modal-footer">
            <button class="back" onclick="closeModal()">Back</button>
            <button class="next" onclick="showMapModal()">Next</button>
        </div>
    </div>
</div>
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Map and Evacuation Centers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="safe" onclick="safety()" >Safe</button>
                <div id="results"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4>QR Code</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- QR Code Canvas (Hidden by default, used for QR generation) -->
                <canvas id="qrCanvas" style="display: none;"></canvas>

                <!-- QR Code Image (Displayed after generation) -->
                <img id="qrImage" alt="QR Code" class="qr-centered" />

                <!-- Download Button -->
                <div id="downloadQR" style="display: none; margin-top: 15px;">
                    <a id="downloadQRLink" href="#" download="qr_code.png" class="btn btn-primary">
                        Download QR Code
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Details Modal -->
<div id="details-container">
    <div id="details-content">
        <span id="close-details-container" onclick="closeDetailsModal()">×</span>
        <div id="details-info"></div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="script.js"></script>
<script>
    const accid = <?php echo $user_id; ?>;
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

                // Show success modal using SweetAlert2
                Swal.fire({
                    title: "Rescue Request Sent!",
                    text: "Your rescue request has been sent successfully. Please wait for further assistance.",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then(() => {
                    // Close the modal after success
                    $('#rescueModal').modal('hide');
                    document.getElementById("quantity").value = ""; // Clear the quantity input
                    document.getElementById("childrenCheck").checked = false; // Uncheck the children checkbox
                    document.getElementById("elderlyCheck").checked = false; // Uncheck the elderly checkbox

                });
            })
            .catch(error => {
                console.error('Error sending data:', error);

                // Show error modal using SweetAlert2
                Swal.fire({
                    title: "Error",
                    text: "There was an issue sending your rescue request. Please try again.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            });
    }

</script>
<script>
    let groupsize_evac = 0;
    let map;
    let routeControl;

    function fetchDataFromDatabase(accid) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `add_evacuees.php?accid=${accid}`, true);  // Send accid as a query parameter
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const fetchedData = JSON.parse(xhr.responseText);
                if (fetchedData.error) {
                    alert(fetchedData.error);
                } else {
                    populateFetchedTable(fetchedData);  // Pass fetched data to load into the table
                }
            }
        };
        xhr.send();
    }
    /// Populate fetchedTable with data
    function populateFetchedTable(data) {
        const tableBody = document.getElementById("fetchedTable").querySelector("tbody");
        tableBody.innerHTML = ""; // Clear existing data

        data.forEach(person => {
            const row = document.createElement("tr");
            row.dataset.id = person.id; // Store the ID for identifying rows
            row.innerHTML = `
            <td>${person.name}</td>
            <td>${person.age}</td>
            <td>${person.sex}</td>
            <td><button onclick="addToEvacuees('${person.id}', '${person.name}', '${person.age}', '${person.sex}', this)">Add</button></td>
        `;
            tableBody.appendChild(row);
        });
    }
    // Add person to evacueesTable
    function addToEvacuees(id, name, age, sex, button) {
        const evacueesTableBody = document.getElementById("evacueesTable").querySelector("tbody");
        const fetchedTableRow = button.parentElement.parentElement; // Row to remove from fetchedTable

        // Check if person is already in the evacueesTable
        const existingRows = Array.from(evacueesTableBody.querySelectorAll("tr"));
        if (existingRows.some(row => row.dataset.id === id)) {
            alert("This person is already in the evacuees list.");
            return;
        }

        // Add a new row to evacueesTable
        const newRow = document.createElement("tr");
        newRow.dataset.id = id; // Store the ID for checking duplicates
        newRow.innerHTML = `
        <td>${name}</td>
        <td>${age}</td>
        <td>${sex}</td>
        <td><button onclick="removeFromEvacuees('${id}', '${name}', '${age}', '${sex}', this)">Remove</button></td>
    `;
        evacueesTableBody.appendChild(newRow);

        // Remove the row from fetchedTable
        fetchedTableRow.remove();

        updateTotalCount();
    }
    // Remove a person from evacueesTable
    function removeFromEvacuees(id, name, age, sex, button) {
        const fetchedTableBody = document.getElementById("fetchedTable").querySelector("tbody");
        const evacueesTableRow = button.parentElement.parentElement; // Row to remove from evacueesTable

        // Add the row back to fetchedTable
        const newRow = document.createElement("tr");
        newRow.dataset.id = id; // Store the ID for identifying rows
        newRow.innerHTML = `
        <td>${name}</td>
        <td>${age}</td>
        <td>${sex}</td>
        <td><button onclick="addToEvacuees('${id}', '${name}', '${age}', '${sex}', this)">Add</button></td>
    `;
        fetchedTableBody.appendChild(newRow);

        // Remove the row from evacueesTable
        evacueesTableRow.remove();

        updateTotalCount();
    }
    // Update the total evacuees count
    function updateTotalCount() {
        const totalCount = document.getElementById("evacueesTable").querySelectorAll("tbody tr").length;
        document.getElementById("totalCount").textContent = totalCount;
    }

    // Initialize modal and fetch data based on accid
    document.getElementById('evac_now').addEventListener('click', function() {
        document.getElementById('modal1').style.display = 'flex'; // Show the modal
        fetchDataFromDatabase(accid);
    });

    function closeModal() {
        document.getElementById('modal1').style.display = 'none';  // Hide the modal
        resetTables();  // Reset tables when modal is closed
    }

    // Reset tables
    function resetTables() {
        // Clear evacuees table
        const evacueesTable = document.getElementById('evacueesTable').querySelector('tbody');
        evacueesTable.innerHTML = ''; // Clear all rows

        // Clear fetched table
        const fetchedTable = document.getElementById('fetchedTable').querySelector('tbody');
        fetchedTable.innerHTML = ''; // Clear all rows

        updateTotal();  // Update the total count to 0
    }

    // Reset tables on back navigation
    window.onpopstate = function() {
        resetTables();
    };

    // Initialize the modal
    const mapModal = new bootstrap.Modal(document.getElementById('mapModal'), {});

    // Show map modal
    function showMapModal() {
        mapModal.show();
        setTimeout(() => {
            map.invalidateSize(); // Fixes Leaflet map resizing issue when inside a modal
        }, 500);

        getUserLocation();
        closeModal();
    }

    function showevacModal() {
        // Remove any existing route from the map, if it exists
        if (routeControl) {
            map.removeControl(routeControl); // Removes the previous route from the map
            routeControl = null; // Reset the routeControl
        }

        // Show the modal
        mapModal.show();

        // Remove the save button from the DOM
        const saveButton = document.getElementById('safe');
        if (saveButton) {
            saveButton.remove(); // Remove the button
        }

        // Resize the map after the modal is shown
        setTimeout(() => {
            map.invalidateSize(); // Fix Leaflet map resizing issue when inside a modal

            // Set the map's zoom level and center it on the marker location
            const markerLat = 14.0689;  // Replace with actual latitude of the marker
            const markerLng = 120.6286; // Replace with actual longitude of the marker

            map.setView([markerLat, markerLng], 17); // Center the map and adjust zoom level
        }, 500);
    }


    // Initialize map
    map = L.map('map').setView([14.0689, 120.6286], 14); // Default center
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Fetch and display evacuation centers
    let evacuationCenters = [];

    fetch("bphp_evac.php")
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data)) {
                alert("Unexpected data format. Please check the server response.");
                return;
            }

            evacuationCenters = data;

            // Add evacuation centers to the map
            const bounds = [];
            data.forEach(center => {
                const { evac_name, evac_lat, evac_lng, evac_capacity, evacuees, photo_base64 } = center;

                // Validate latitude and longitude
                if (typeof evac_lat !== "number" || typeof evac_lng !== "number") {
                    console.warn(`Invalid coordinates for center: ${evac_name}`);
                    return;
                }

                // Add marker to the map
                const marker = L.marker([evac_lat, evac_lng]).addTo(map);

                // Add click event to the marker
                marker.on('click', () => {
                    const remainingCapacity = evac_capacity - evacuees;

                    // Create content for the modal
                    const modalContent = `
                    <strong>Name:</strong> ${evac_name}<br>
                    <strong>Capacity:</strong> ${evac_capacity}<br>
                    <strong>Evacuees:</strong> ${evacuees}<br>
                    <strong>Remaining Capacity:</strong> ${remainingCapacity}<br>
                    <img src="data:image/jpeg;base64,${photo_base64}" alt="${evac_name}" style="max-width: 100%; margin-top: 10px;">
                `;
                    showDetailsModal(modalContent);

                    // Zoom to the clicked marker location with zoom level 19
                    map.setView([evac_lat, evac_lng], 19);
                });

                // Add the coordinates to bounds for fitting the map to markers
                bounds.push([evac_lat, evac_lng]);
            });

            // Adjust map to show all evacuation centers
            if (bounds.length > 0) {
                map.fitBounds(bounds);
            }
        })
        .catch(error => {
            console.error("Error fetching evacuation centers:", error);
            alert("An error occurred while loading evacuation centers.");
        });

    // Function to show the details modal
    function showDetailsModal(content) {
        const detailsContainer = document.getElementById('details-container');
        document.getElementById('details-info').innerHTML = content;
        detailsContainer.style.display = 'block';
    }

    // Function to close the details modal
    function closeDetailsModal() {
        document.getElementById('details-container').style.display = 'none';
    }


    // Function to show the details modal
    function showDetailsModal(content) {
        const detailsContainer = document.getElementById('details-container');
        const mapContainer = document.getElementById('map-container');

        // Update modal content
        document.getElementById('details-info').innerHTML = content;

        // Ensure details modal is displayed
        detailsContainer.style.display = 'block';

        // Bring the details modal to the front (make sure it's above the map modal)
        detailsContainer.style.position = 'fixed';
        detailsContainer.style.zIndex = '9999'; // High enough to be on top of the map modal

        // Adjust the position of the modal if needed (e.g., center it on the screen)
        detailsContainer.style.top = '20%'; // Customize based on your layout
        detailsContainer.style.left = '50%';
        detailsContainer.style.transform = 'translateX(-50%)';
    }

    // Function to close the details modal
    function closeDetailsModal() {
        document.getElementById('details-container').style.display = 'none';
    }



    // Function to get and pinpoint user location
    function getUserLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    const { latitude, longitude } = position.coords;

                    // Add user location marker to the map
                    const userMarker = L.marker([latitude, longitude], { icon: L.icon({
                            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34]
                        })}).addTo(map);

                    userMarker.bindPopup("<strong>Your Location</strong>").openPopup();

                    // Center map to user location
                    map.setView([latitude, longitude], 14);

                    // Find the closest evacuation center and route to it
                    findClosestEvacuationArea(latitude, longitude);
                },
                error => {
                    console.error("Error getting location:", error);
                    alert("Unable to fetch your location. Please check your permissions.");
                }
            );
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    function findClosestEvacuationArea(userLat, userLng) {
        const groupSize =groupsize_evac; // Get group size from input

        let closestSuitableCenter = null;
        let shortestDistance = Infinity;

        let fallbackCenter = null; // Closest center even if it doesn’t meet the criteria
        let fallbackDistance = Infinity;

        evacuationCenters.forEach(center => {
            const remainingCapacity = center.evac_capacity - center.evacuees;
            const distance = getDistance(userLat, userLng, center.evac_lat, center.evac_lng);

            // Check if the center meets the criteria
            if (remainingCapacity >= groupSize && distance < shortestDistance) {
                closestSuitableCenter = center;
                shortestDistance = distance;
            }

            // Always track the absolute closest center as a fallback
            if (distance < fallbackDistance) {
                fallbackCenter = center;
                fallbackDistance = distance;
            }
        });

        // Determine the center to use
        const selectedCenter = closestSuitableCenter || fallbackCenter;

        if (!selectedCenter) {
            alert("No evacuation centers are available.");
            return;
        }

        // Show warning if using fallback center
        if (!closestSuitableCenter) {
            alert(`No evacuation center has enough capacity for your group. Routing to the closest center: ${fallbackCenter.evac_name}`);
        }

        // Highlight the selected center on the map
        map.eachLayer(layer => {
            if (layer instanceof L.Marker) {
                const { lat, lng } = layer.getLatLng();
                if (lat === selectedCenter.evac_lat && lng === selectedCenter.evac_lng) {
                    layer.bindPopup(`<strong>Selected Evacuation Area:</strong> ${selectedCenter.evac_name}<br>
            <strong>Remaining Capacity:</strong> ${selectedCenter.evac_capacity - selectedCenter.evacuees}`).openPopup();
                }
            }
        });

        // Draw a route to the selected center
        routeControl = L.Routing.control({
            waypoints: [
                L.latLng(userLat, userLng),
                L.latLng(selectedCenter.evac_lat, selectedCenter.evac_lng)
            ],
            routeWhileDragging: true,
            createMarker: function () { return null; } // Suppress default routing markers
        }).addTo(map);

        // Remove step-by-step directions
        const routingControlContainer = routeControl.getContainer();
        const controlContainerParent = routingControlContainer.parentNode;
        controlContainerParent.removeChild(routingControlContainer);
    }
    // Function to calculate the distance between two coordinates (Haversine formula)
    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of the Earth in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c; // Distance in km
    }
</script>
<script>
    const qrModal = new bootstrap.Modal(document.getElementById('qrModal'), {});

    function safety() {
        const fullname = "<?php echo $full_name; ?>";
        const user = "<?php echo $user_id; ?>";
        const age = "<?php echo $age; ?>";
        const sex = "<?php echo $sex; ?>";

        if (!fullname || !user) {
            alert("Please enter both Full Name and User ID.");
            return;
        }

        // Collect evacuees' data
        const evacueesData = [];
        const rows = document.querySelectorAll('#evacueesTable tbody tr');
        rows.forEach(row => {
            const name = row.cells[0].textContent.trim();
            const evacAge = row.cells[1].textContent.trim();
            const evacSex = row.cells[2].textContent.trim();
            evacueesData.push({ name, age: evacAge, sex: evacSex });
        });

        // Combine the primary user data and evacuees' data
        const qrData = {
            primary: { fullname, age, sex },
            evacuees: evacueesData,
            userId: user
        };

        // Generate QR code
        const qrCanvasElement = document.getElementById('qrCanvas');
        QRCode.toCanvas(qrCanvasElement, JSON.stringify(qrData), function (error) {
            if (error) {
                console.error("QR Code generation failed:", error);
                return;
            }

            const qrBase64 = qrCanvasElement.toDataURL('image/png');

            // Display the QR code in the modal
            const qrImageElement = document.getElementById('qrImage');
            qrImageElement.src = qrBase64;

            // Set up the download link
            const downloadLink = document.getElementById('downloadQRLink');
            downloadLink.href = qrBase64;
            downloadLink.download = `${fullname}_QR.png`;
            document.getElementById('downloadQR').style.display = 'block';

            // Close the first modal and the map modal (if open), then open the QR modal
            const firstModal = document.getElementById('firstModal');
            const mapModal = document.getElementById('mapModal');
            const qrModal = new bootstrap.Modal(document.getElementById('qrModal'));

            if (firstModal && bootstrap.Modal.getInstance(firstModal)) {
                bootstrap.Modal.getInstance(firstModal).hide(); // Close the first modal
            }

            if (mapModal && bootstrap.Modal.getInstance(mapModal)) {
                bootstrap.Modal.getInstance(mapModal).hide(); // Close the map modal
            }

            qrModal.show();

            // Send the QR code data and JSON data to the server
            sendQRToServer(qrBase64, qrData);
        });
    }

    // Function to send QR code and data to the server
    function sendQRToServer(qrBase64, qrData) {
        const formData = new FormData();
        formData.append('qrCode', qrBase64); // QR code as Base64
        formData.append('qrData', JSON.stringify(qrData)); // JSON data for qrText

        fetch('qr.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                console.log("QR code and data saved successfully!");
                console.log(data);
            })
            .catch(error => {
                console.error("Error saving data:", error);
            });
    }
</script>

</body>
</html>
