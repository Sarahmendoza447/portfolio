<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evacuation Modal</title>
    <style>
        /* Modal styling */
        #modal1  {
            display: flex;
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

        #modal1  .modal-content {
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
    </style>
</head>
<body>

<div class="modal" id="modal1">
    <div class="modal-content">
        <div class="modal-header">Evacuation</div>

        <!-- Top Table (Fetched Data) -->
        <table class="data-table" id="fetchedTable">
            <thead>
            <tr>
                <th>Name</th>
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
            <button class="back">Back</button>
            <button class="next">Next</button>
        </div>
    </div>
</div>

<script>
    // Assuming you have the accid available in the front-end,
    // here is how you can pass it to the backend:
    const accid = 1;  // Example accid, you can retrieve it dynamically.

    // Function to fetch data from the PHP backend
    function fetchDataFromDatabase(accid) {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `add_evacuees.php?accid=${accid}`, true);  // Send accid as a query parameter
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const fetchedData = JSON.parse(xhr.responseText);
                if (fetchedData.error) {
                    alert(fetchedData.error);
                } else {
                    loadFetchedData(fetchedData);  // Pass fetched data to load into the table
                }
            }
        };
        xhr.send();
    }

    // Load fetched data into the top table
    function loadFetchedData(fetchedData) {
        const fetchedTable = document.getElementById('fetchedTable').querySelector('tbody');
        fetchedTable.innerHTML = ''; // Clear existing rows
        fetchedData.forEach(item => {
            const row = document.createElement('tr');
            row.dataset.id = item.id; // Add a unique identifier
            row.innerHTML = `
                <td>${item.name}</td>
                <td><button onclick="addFetchedName('${item.id}', '${item.name}')">+</button></td>
            `;
            fetchedTable.appendChild(row);
        });
    }

    // Add fetched name to evacuees list and remove from fetched table
    function addFetchedName(id, name) {
        addNameToTable(id, name);

        // Remove name from fetched table
        const fetchedTable = document.getElementById('fetchedTable').querySelector('tbody');
        const row = fetchedTable.querySelector(`[data-id="${id}"]`);
        if (row) row.remove();

        updateTotal();
    }

    // Add a name to the evacuees table
    function addNameToTable(id, name) {
        const evacueesTable = document.getElementById('evacueesTable').querySelector('tbody');
        const row = document.createElement('tr');
        row.dataset.id = id; // Add a unique identifier
        row.innerHTML = `
            <td>${name}</td>
            <td><button onclick="removeRow('${id}', '${name}')">-</button></td>
        `;
        evacueesTable.appendChild(row);
    }

    // Remove a name from evacuees table and return it to fetched table
    function removeRow(id, name) {
        const evacueesTable = document.getElementById('evacueesTable').querySelector('tbody');
        const row = evacueesTable.querySelector(`[data-id="${id}"]`);
        if (row) row.remove();

        // Return name to fetched table
        const fetchedTable = document.getElementById('fetchedTable').querySelector('tbody');
        const newRow = document.createElement('tr');
        newRow.dataset.id = id; // Add a unique identifier
        newRow.innerHTML = `
            <td>${name}</td>
            <td><button onclick="addFetchedName('${id}', '${name}')">+</button></td>
        `;
        fetchedTable.appendChild(newRow);

        updateTotal();
    }

    // Update the total count of evacuees
    function updateTotal() {
        const evacueesTable = document.getElementById('evacueesTable').querySelector('tbody');
        const totalCount = evacueesTable.children.length;
        document.getElementById('totalCount').innerText = totalCount;
    }

    // Initialize modal and fetch data based on accid
    document.addEventListener('DOMContentLoaded', function() {
        fetchDataFromDatabase(accid);
    });
</script>

</body>
</html>
