<?php
// Database connection (update with your database credentials)
global $conn;
include 'db-connection.php';

// Query to get the total donation
$sql_total_donation = "SELECT SUM(Amount) AS total_donation FROM donator";
$result_total_donation = $conn->query($sql_total_donation);
$total_donation = 0;
if ($result_total_donation->num_rows > 0) {
    $row = $result_total_donation->fetch_assoc();
    $total_donation = $row['total_donation'];
}

// Query to get the total expenses
$sql_total_expenses = "SELECT SUM(Amount) AS total_expenses FROM expenses";
$result_total_expenses = $conn->query($sql_total_expenses);
$total_expenses = 0;
if ($result_total_expenses->num_rows > 0) {
    $row = $result_total_expenses->fetch_assoc();
    $total_expenses = $row['total_expenses'];
}

// Assuming remaining balance is calculated as total donation minus total expenses
$remaining_balance = $total_donation - $total_expenses;


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILONG Mobile Interface</title>
    <link href="style.css" rel="stylesheet">
    <style>
        /* Donation Card Container */
        .donation-container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            margin-top: 80px;
            color: #1f3c52;
        }

        /* Expenses Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Transparent black background */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            color: #333; /* White font color */
            padding: 20px;
            border-radius: 10px;
            max-width: 80%;
            max-height: 80%;
            overflow-y: auto;
        }

        .modal-table {
            width: 100%;
            border-collapse: collapse;
        }

        .modal-table th, .modal-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .modal-table th {
            background-color: #f2f2f2; /* Light gray background for the table header */
            color: #555; /* Dark gray font color for headers */
        }

        .modal-table td {
            background-color: #fafafa; /* Light gray background for table rows */
            color: #333; /* Dark gray font color for table data */
        }

        .modal-image {
            max-width: 100%;
            max-height: 400px;
            display: block;
            margin: 10px 0;
        }

        .close-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            margin-top: 10px;
        }

        .close-btn-bottom {
            background-color: #f44336; /* Red background for the button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        .close-btn-bottom:hover {
            background-color: #d32f2f;
        }
        .modal-image {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
<div class="navbar">
    <div class="logo">
        <img src="images/SILONG_white.png" alt="SILONG Logo" class="logo-img">
    </div>
    <div class="nav-icons">
        <a href="#" class="nav-item"><img src="images/notification-icon.png" alt="Notification" class="icon-img"></a>
        <a href="#" class="nav-item"><img src="images/user-icon.png" alt="User" class="icon-img"></a>
    </div>
</div>

<!-- Tabs -->
<div class="tabs">
    <div class="tab-item active" data-target="rescue" href="tryyy.php">
        <img src="images/rescue-icon.png" alt="Rescue Icon" class="tab-icon" >
        <span>Rescue</span>
    </div>
    <div class="tab-item" data-target="evacuate" href="tryyy.php">
        <img src="images/evacuate-icon.png" alt="Evacuate Icon" class="tab-icon">
        <span>Evacuate</span>
    </div>
    <div class="tab-item" data-target="relief" href="tryyy.php">
        <img src="images/relief-icon.png" alt="Relief Goods Icon" class="tab-icon">
        <span>Relief Goods</span>
    </div>
    <div class="tab-item" data-target="donation" href="tryyy.php">
        <img src="images/donation-icon.png" alt="Donation Icon" class="tab-icon">
        <span>Donation</span>
    </div>
</div>

<div class="donation-container" id="donation-container">
    <h2 class="donation-header">Donation</h2>
    <div class="donation-details">
        <p><strong>Total Donation:</strong></p>
        <output class="output-box" id="total-donation">$<?php echo number_format($total_donation, 2); ?></output>

        <p><strong>Remaining Donation:</strong></p>
        <output class="output-box" id="remaining-donation">$<?php echo number_format($remaining_balance, 2); ?></output>

        <p><strong>Total Expenses:</strong></p>
        <output class="output-box" id="total-expenses">$<?php echo number_format($total_expenses, 2); ?></output>
    </div>
    <div class="donation-buttons">
        <button class="btn" id="expenses-button">Expenses</button>
    </div>
</div>

<!-- Expenses Modal -->
<div id="expenses-modal" class="modal">
    <div class="modal-content">
        <h2>Expenses</h2>
        <table class="modal-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="expenses-table-body">
            <!-- Data will be populated here by JavaScript -->
            </tbody>
        </table>
        <button id="close-btn" class="close-btn">Close</button>
    </div>
</div>

<!-- Image Modal (for viewing images) -->
<!-- Image Modal for Viewing the Receipt -->
<!-- Image Modal for Viewing the Receipt -->
<div id="image-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <img id="expense-image" class="modal-image" alt="Expense Image" />
        <span id="close-image-modal" class="close-btn-bottom">Close</span>
    </div>
</div>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the modal and button elements
        const modal = document.getElementById("expenses-modal");
        const expensesButton = document.getElementById("expenses-button");
        const closeBtn = document.getElementById("close-btn");
        const closeImageModalBtn = document.getElementById("close-image-modal"); // Fixed selector for the close button inside image modal

        // Show the modal when the "Expenses" button is clicked
        expensesButton.addEventListener('click', function() {
            // Fetch data from the server using AJAX
            fetchExpensesData();

            // Display the modal
            modal.style.display = "flex";
        });

        // Close the modal when the close button is clicked
        closeBtn.addEventListener('click', function() {
            modal.style.display = "none";
        });

        // Close the image modal when the close button inside the image modal is clicked
        closeImageModalBtn.addEventListener('click', function() {
            const imageModal = document.getElementById("image-modal");
            imageModal.style.display = "none"; // Hide the image modal
        });

        // Close the modal if the user clicks outside of the modal
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });


    // Function to fetch expenses data from the database using AJAX
        function fetchExpensesData() {
            // AJAX request to get expenses data from your server-side script
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'getexpenses.php', true); // Change the URL to your server-side script
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const expensesData = JSON.parse(xhr.responseText);

                    // Get the table body element
                    const tableBody = document.getElementById("expenses-table-body");

                    // Clear any previous data
                    tableBody.innerHTML = '';

                    // Populate the table with expenses data
                    expensesData.forEach(function(expense) {
                        const row = document.createElement("tr");

                        // ID column
                        const idCell = document.createElement("td");
                        idCell.textContent = expense.ExpensesID;
                        row.appendChild(idCell);

                        // Description column
                        const descriptionCell = document.createElement("td");
                        descriptionCell.textContent = expense.description;
                        row.appendChild(descriptionCell);

                        // Amount column
                        const amountCell = document.createElement("td");
                        amountCell.textContent = expense.Amount;
                        row.appendChild(amountCell);

                        // Add a view button in the last column
                        const viewCell = document.createElement("td");
                        const viewButton = document.createElement("button");
                        viewButton.textContent = "View Receipt";
                        viewButton.classList.add("view-btn");
                        viewButton.addEventListener('click', function() {
                            viewExpenseImage(expense.Receipt); // Pass the base64-encoded image data
                        });
                        viewCell.appendChild(viewButton);
                        row.appendChild(viewCell);

                        tableBody.appendChild(row);
                    });
                } else {
                    console.error('Failed to fetch data: ' + xhr.statusText);
                }
            };
            xhr.send();
        }

        // Function to display the receipt image when "View" is clicked
        function viewExpenseImage(imageBase64) {
            // Get the image element in the modal
            const image = document.getElementById("expense-image");

            // Set the image source to the base64-encoded image data
            image.src = imageBase64;

            // Show the image modal
            const imageModal = document.getElementById("image-modal");
            imageModal.style.display = "flex";
        }

        // Close the image modal when the image modal is clicked
        const imageModal = document.getElementById("image-modal");
        imageModal.addEventListener('click', function(event) {
            if (event.target === imageModal) {
                imageModal.style.display = "none";
            }
        });

    });
    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', function(event) {
            // Get the target PHP file for the clicked tab
            const targetFile = this.getAttribute('href');

            // Redirect to the target PHP file
            window.location.href = targetFile;
        });
    });


</script>
</body>
</html>
