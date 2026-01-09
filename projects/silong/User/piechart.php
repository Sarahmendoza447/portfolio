<?php
include 'db-connection.php';
// SQL Query to fetch sex and age data and classify into categories
$query = "
    SELECT Sex, 
           CASE 
               WHEN Age <= 12 THEN 'Kids'
               WHEN Age BETWEEN 13 AND 19 THEN 'Teenager'
               WHEN Age BETWEEN 20 AND 59 THEN 'Adult'
               WHEN Age >= 60 THEN 'Senior'
           END AS AgeCategory
    FROM evacueelist
";

// Execute the query
$result = $conn->query($query);

// Arrays to hold the counts of each category
$maleCount = 0;
$femaleCount = 0;
$maleAgeCounts = ['Kids' => 0, 'Teenager' => 0, 'Adult' => 0, 'Senior' => 0];
$femaleAgeCounts = ['Kids' => 0, 'Teenager' => 0, 'Adult' => 0, 'Senior' => 0];

// Process the results and count the occurrences
while ($row = $result->fetch_assoc()) {
    if ($row['Sex'] === 'Male') {
        $maleCount++;
        $maleAgeCounts[$row['AgeCategory']]++;
    } elseif ($row['Sex'] === 'Female') {
        $femaleCount++;
        $femaleAgeCounts[$row['AgeCategory']]++;
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sex and Age Distribution Donut Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Styling for the canvas */
        #ageSexChart {
            width: 400px !important;
            height: 400px !important;
            margin: 20px auto;
            display: block;
            border: 1px solid #ddd;
            background-color: transparent;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">Sex and Age Distribution of Evacuees</h2>
    <canvas id="ageSexChart"></canvas>

    <script>
        let chart = null;

        // Data fetched from PHP
        const maleCount = <?php echo $maleCount; ?>;
        const femaleCount = <?php echo $femaleCount; ?>;
        const maleAgeCounts = <?php echo json_encode($maleAgeCounts); ?>;
        const femaleAgeCounts = <?php echo json_encode($femaleAgeCounts); ?>;

        // Labels for age categories
        const ageCategories = ['Kids', 'Teenager', 'Adult', 'Senior'];

        // Data for the chart
        const maleAgeData = ageCategories.map(category => maleAgeCounts[category]);
        const femaleAgeData = ageCategories.map(category => femaleAgeCounts[category]);

        // Sex data (inner ring of the donut)
        const sexData = [maleCount, femaleCount];  // Male and Female counts

        // Color configuration for Sex (inner) and Age distribution (outer)
        const sexColors = ['#203864', '#385723'];  // Male and Female colors for inner ring
        const maleAgeColors = ['#385c9c', '#b4c7e7', '#beceea', '#e2f0d9'];  // Male Age colors
        const femaleAgeColors = ['#4f7e2f', '#70ad47', '#a9d18e', '#c4dfb3'];  // Female Age colors

        // Update the chart
        const ctx = document.getElementById('ageSexChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'], // Labels for the inner part (Sex distribution)
                datasets: [
                    {
                        label: 'Sex Distribution',
                        data: sexData, // Male and Female counts for the inner ring
                        backgroundColor: sexColors, // Colors for Male and Female
                        borderColor: '#fff',
                        borderWidth: 2,
                        cutout: '50%', // Inner radius for the donut (Sex distribution)
                    },
                    {
                        label: 'Male Age Distribution',
                        data: maleAgeData, // Male age distribution data for outer ring
                        backgroundColor: maleAgeColors, // Male age distribution colors
                        borderColor: '#fff',
                        borderWidth: 2,
                        cutout: '75%', // Increase the outer ring to show age distribution
                    },
                    {
                        label: 'Female Age Distribution',
                        data: femaleAgeData, // Female age distribution data for outer ring
                        backgroundColor: femaleAgeColors, // Female age distribution colors
                        borderColor: '#fff',
                        borderWidth: 2,
                        cutout: '75%', // Increase the outer ring to show age distribution
                    }
                ]
            },
            options: {
                responsive: true,
                animation: {
                    duration: 0  // Disable animations
                },
                plugins: {
                    legend: {
                        display: false,  // Hide the legend
                    },
                    tooltip: {
                        enabled: false  // Disable tooltips
                    }
                }
            }
        });
    </script>

</body>
</html>
