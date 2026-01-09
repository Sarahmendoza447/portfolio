<?php
global $EIDs;
include 'donut.php';
include 'columnchart.php';
include 'barchart.php';
include 'linechart.php';
include 'scoreboard.php';
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goods Request Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="donut.css">
    <style>


body {
    font-family: 'Roboto', sans-serif; 
    margin: 0;
    padding: 0;
    background-color: #ffff;
    }
    
    
#barchartContainer, 
.chart-container {
    position: relative;
    width: 45%; /* Adjust width to fit two charts side by side */
    margin: 10px auto; /* Spacing between the charts */
    background-color: transparent !important; /* Transparent background */
    box-shadow: none; /* Remove shadows */
  
    border-radius: 10px; /* Optional rounded corners */
    border: none; /* Remove borders */
  
}

/* Transparent Chart Canvas */
#barchartCanvas, 
#goodsChart {
    width: 100% !important;
    height: 300px !important;
    display: block;
    background-color: transparent !important; /* Transparent canvas background */
}

/* Filters Positioning and Styling */
#barchartFilters, 
#filterContainer {
   
    top: 10px;
    right: 10px;
    display: flex;
    gap: 10px;
    z-index: 2;
}

#barchartFilters select, 
#barchartFilters input[type="date"],
#filterContainer select {
    font-size: 14px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background: none; /* Transparent background for filter inputs */
}

/* Flex Container for the layout */
.flex-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin: 20px;
}



/* Filters Section */
.filters {
    display: flex;
    justify-content: space-between;
    
}

/* Input and Select fields */
.input-field {
    padding: 8px 15px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 14px;
    background-color: #fff;
    transition: all 0.3s ease;
}

.input-field:focus {
    border-color: #3498db; /* Focus color for input */
    outline: none;
    box-shadow: 0 0 8px rgba(52, 152, 219, 0.3); /* Focus shadow */
}
/* Canvas Styles (Chart Areas) */
canvas {
    width: 100%;
    height: 300px; /* Adjusted height */
}

/* Custom Bar Chart Styling */
#barchartCanvas {
    background-color: #fff;
    border-radius: 8px;
   
}

/* Hover Shine Effect on Bars */
.chart-container canvas .bar:hover {
    background: linear-gradient(45deg, #1a548d, #B81701); /* Gradient on hover */
    transform: scale(1.1); /* Slight increase in size */
    box-shadow: 0 0 10px rgba(255, 255, 255, 0.6); /* Shine effect */
    transition: all 0.3s ease-in-out; /* Smooth hover transition */
}

/* Optional Shine Effect for the entire Bar on hover */
.chart-container canvas .bar {
    transition: all 0.3s ease-in-out;
    background: linear-gradient(45deg, #1a548d, #B81701); /* Default gradient */
}



/* Optional General Styling for Canvas if Needed */
canvas {
    background-color: transparent !important; /* Ensure all canvases are transparent */
    border: none; /* No borders on canvas */
}

/* Style for the scoreboard container */
.scoreboard {
    display: flex;
    justify-content: space-around;
    margin: 10px; /* Reduced margin for a more compact layout */
    height: 120px; /* Reduced height for a smaller appearance */
}

/* Style for each scoreboard item */
.scoreboard-item {
    background-color: transparent;
    padding: 6px; /* Reduced padding */
    border-radius: 4px; /* Slightly smaller corner radius */
    text-align: center;
    width: 22%; /* Reduced width for smaller items */
    border: 1px solid #ddd; /* Thinner border */
    position: relative;
}

/* Left colored line on each scoreboard */
.scoreboard-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 6px; /* Thinner line */
    background-color: #3498db; /* Retain blue color */
}

/* Title for the scoreboard item */
.scoreboard-item h2 {
    font-size: 1.5rem; /* Smaller font size */
    margin: 12px 0; /* Adjust spacing */
    color: #333;
}

/* Description text inside the scoreboard */
.scoreboard-item p {
    font-size: 0.9rem; /* Smaller text */
    color: #555;
}

/* Value text styling */
.scoreboard-item .value {
    font-size: 2rem; /* Smaller font size */
    font-weight: bold;
    color: #2d2d2d;
    margin-top: -10px; /* Adjust spacing above the value */
}

/* Container for the Column Chart */
#columnchart-container {
    height: 350px;
    position: relative; /* Ensure proper positioning */
    width:  50%; /* Adjust width as needed */  
    margin: 10px auto; /* Center the container and reduce spacing */
    margin-top: -50%; /* Remove any top margin to move it upward */
    margin-right: 50%;
    margin-left: 50px;
    padding: 10px; /* Adjust padding for spacing inside the container */
    background-color: transparent; /* Maintain transparency */
}

/* Adjust Canvas */
#columnChart {
    width: 100% !important;
    height: 200px; /* Adjust the height as needed */
    display: block;
    background-color: transparent; /* Ensure the chart remains transparent */
}
.donut-chart-wrapper {
    padding-left: 60%;
   
    position: relative;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    background-color: transparent;
    width: 30%;
    gap: 0px;
    margin-top: 20px; /* Reduced margin-top to move it upwards */
}

.donut-chart-container {
    position: relative;
    width: 80%;
    text-align: center;
}

#ageDistributionChart {
    width: 110%;
    height: 500px;
    display: block;
    margin: 0 auto; /* Center horizontally */
    padding-top: 0; /* Ensure no padding pushes it down */
}

/* Flexbox for vertical alignment */
.scoreboard-container-donut {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    gap: 10px; /* Added gap between filter and scoreboards */
    margin-top: 10px; /* Reduced margin-top for tighter spacing */
    width: 50%;
}

/* Common styling for each scoreboard item */
.scoreboard-item-female-donut,
.scoreboard-item-male-donut {
    background: linear-gradient(to right, #B81701, #1a548d); /* Gradient from red to blue */
    padding: 20px;
    border-radius: 10px;
    color: white; /* White text */
    text-align: center;
    position: relative;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Light shadow for depth */
    min-width: 200px; /* Set a minimum width for consistency */
    overflow: hidden; /* Hide the shimmer outside the item */
    transition: transform 0.3s ease-in-out; /* Smooth expand effect */
}
/* Hover effect - expand the scoreboard item */
.scoreboard-item-female-donut:hover,
.scoreboard-item-male-donut:hover {
    transform: scale(1.05); /* Slight expansion */
}
/* Shine effect */
.scoreboard-item-female-donut::before,
.scoreboard-item-male-donut::before {
    content: ''; /* No text, just the shimmer */
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.4); /* Light shine effect */
    transform: skewX(-30deg); /* Slant the shimmer for a stylish effect */
    transition: left 0.5s ease-in-out;
}

/* Hover shine animation */
.scoreboard-item-female-donut:hover::before,
.scoreboard-item-male-donut:hover::before {
    left: 100%; /* Move the shimmer from left to right */
}

/* Color for male donut */
.male-donut {
    background-color: #36A2EB;
}

/* Color for female donut */
.female-donut {
    background-color: #FF6384;
}

/* Styling for the value text */
.scoreboard-item-female-donut .value,
.scoreboard-item-male-donut .value {
    font-size: 1.5em;
    font-weight: 600;
    margin-top: 10px;
    color: white; /* Ensure value text is white */
}
/* Specific color for male scoreboard */
.scoreboard-item-male-donut {
    background: linear-gradient(to right, #1a548d, #B81701); /* Inverse color gradient for male */
}

/* Styling for the dropdown */
select {
    font-size: 1rem;
    padding: 8px;
    margin: 10px 0;
    border-radius: 5px;
}

/* Dropdown styling */
.dropdown-wrapper select {
    padding: 10px;
    font-size: 1em;
    border-radius: 5px;
    border: 1px solid #ccc;
    background-color: #f7f7f7;
}
/* Styling for the heading inside each item */
.scoreboard-item-female-donut h2,
.scoreboard-item-male-donut h2 {
    font-size: 1.2em;
    margin-bottom: 10px;
    font-weight: bold;
    color: white; /* Ensure heading text is white */
}

/* Styling for the scoreboard container */
.scoreboard {
    display: flex;
    justify-content: space-around;
    gap: 20px;
    margin: 20px;
}

/* Common styling for each scoreboard item */
.scoreboard-item {
    background: linear-gradient(to right, #B81701, #1a548d); /* Gradient from red to blue */
    padding: 20px;
    border-radius: 10px;
    color: white; /* Ensure text color is white */
    text-align: center;
    transition: transform 0.3s ease-in-out; /* Smooth expand effect */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Light shadow for depth */
    min-width: 200px; /* Set a minimum width for consistency */
    position: relative; /* Necessary for positioning the shine effect */
    overflow: hidden; /* Hide the shimmer outside the item */
}

/* Shine effect */
.scoreboard-item::before {
    content: ''; /* No text, just the shimmer */
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.4); /* Light shine effect */
    transform: skewX(-30deg); /* Slant the shimmer for a stylish effect */
    transition: left 0.5s ease-in-out;
}

/* Hover effect - expand the scoreboard item */
.scoreboard-item:hover {
    transform: scale(1.05); /* Slight expansion */
}

/* Hover shine animation */
.scoreboard-item:hover::before {
    left: 100%; /* Move the shimmer from left to right */
}

/* Styling for the heading inside each item */
.scoreboard-item h2 {
    font-size: 1.2em;
    margin-bottom: 10px;
    font-weight: bold;
    color: white; /* Ensure heading text is white */
}

/* Styling for the value text */
.scoreboard-item .value {
    font-size: 2.5em;
    font-weight: 600;
    margin-top: 10px;
    color: white; /* Ensure value text is white */
}
/* Flex Container for the layout */
.flex-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin: 20px;
    flex-wrap: wrap; /* Allow items to wrap on smaller screens */
}

/* Media Queries for Responsiveness */
@media (max-width: 1200px) {
    .flex-container {
        flex-direction: column;
        align-items: center;
    }

    #barchartContainer,
    #columnchart-container {
        width: 90%; /* 90% width on medium screens */
    }

    .scoreboard {
        flex-direction: column;
        align-items: center;
    }
}

@media (max-width: 768px) {
    .flex-container {
        flex-direction: column;
        align-items: center;
    }

    #barchartContainer,
    #columnchart-container,
    .donut-chart-wrapper {
        width: 100%; /* Full width for small screens */
    }

    .scoreboard {
        flex-direction: column;
        align-items: center;
    }

    /* Adjust chart heights for smaller screens */
    #barchartCanvas, 
    #goodsChart, 
    #columnChart {
        height: 250px;
    }
}

@media (max-width: 480px) {
    #barchartContainer,
    #columnchart-container {
        width: 100%; /* Full width on small screens */
    }

    .scoreboard-item {
        width: 100%; /* Stack scoreboard items vertically */
        margin: 10px 0;
    }
}

</style>

</head>
<body>
<!-- Dashboard Scoreboards -->
<div class="scoreboard">

    <!-- Total Non-Evacuees -->
    <div class="scoreboard-item">
        <h2>Total Residents</h2>
        <p class="value"><?php echo number_format($totalResidents); ?></p>
    </div>

    <!-- Total Evacuees -->
    <div class="scoreboard-item">
        <h2>Evacuees</h2>
        <p class="value"><?php echo number_format($totalEvacuees); ?></p>
    </div>
    
    <!-- Total Donation -->
    <div class="scoreboard-item">
        <h2>Total Donation</h2>
        <p class="value">₱<?php echo number_format($totalDonation, 2); ?></p>
    </div>
</div>

<!-- Wrapper for Donut Chart and Scoreboards -->
<div class="donut-chart-wrapper">
    <!-- Donut Chart for Age Distribution -->
    <div class="donut-chart-container">
        <canvas id="ageDistributionChart"></canvas>
    </div>
    
    <!-- Scoreboards and Filter Section -->
    <div class="scoreboard-container-donut">
        <!-- Dropdown for selecting EID -->
        <div class="dropdown-wrapper">
            <select id="EID" onchange="filterData()">
                <option value="">All Evacuations</option>
                <?php while ($EID = $EIDs->fetch_assoc()): ?>
                    <option value="<?php echo $EID['EID']; ?>" <?php echo ($EID['EID'] == $EIDFilter ? 'selected' : ''); ?>>
                        <?php echo $EID['EID']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Scoreboard for Female -->
        <div class="scoreboard-item-female-donut female-donut">
            <h2>Female</h2>
            <div class="value" id="totalFemale">0</div>
        </div>

        <!-- Scoreboard for Male -->
        <div class="scoreboard-item-male-donut male-donut">
            <h2>Male</h2>
            <div class="value" id="totalMale">0</div>
        </div>
    </div>
</div>


<div id="columnchart-container">
    <canvas id="columnChart"></canvas>
</div>


<div class="flex-container">
    <!-- Bar Chart Container -->
    <div id="barchartContainer" class="chart-container">
        <div id="barchartFilters" class="filters">
            <input type="date" id="barchartDateChooser" onchange="updateChart()" class="input-field">
            <select id="barchartEvacIDFilter" onchange="updateChart()" class="input-field">
                <option value="">All Evacuations</option>
                <?php foreach ($evacuationIDs as $evacID): ?>
                    <option value="<?= $evacID ?>"><?= $evacID ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <canvas id="barchartCanvas"></canvas>
    </div>

        <!-- Line Chart Container -->
        <div class="chart-container">
            <div id="filterContainer">
                <select id="evID" onchange="applyFilter()">
                    <option value="">All Evacuations</option>
                    <?php while ($row = $evacResult->fetch_assoc()) { ?>
                        <option value="<?php echo $row['EvacID']; ?>" <?php echo (isset($_GET['EvacID']) && $_GET['EvacID'] == $row['EvacID']) ? 'selected' : ''; ?>>
                            EvID: <?php echo $row['EvacID']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <canvas id="goodsChart"></canvas>
        </div>
        
    </div>
    


     

    <script>
   let chart = null;

// Get current date in the format YYYY-MM-DD
const today = new Date().toISOString().split('T')[0];

// Set today's date as the default value in the date input
document.getElementById('barchartDateChooser').value = today;

// Fetch and update the chart for today's date when the page loads
updateChart();

// Function to fetch data and update the chart
function updateChart() {
    const selectedDate = document.getElementById('barchartDateChooser').value;
    const selectedEvacID = document.getElementById('barchartEvacIDFilter').value;
    if (!selectedDate) return;

    // Fetch data from the server with the selected EvacID and date
    const url = "<?php echo $_SERVER['PHP_SELF']; ?>?date=" + selectedDate + (selectedEvacID ? "&evacID=" + selectedEvacID : "");

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.GoodsName);
            const reqNums = data.map(item => item.ReqNum);

            // Create a unique gradient for each GoodsName
            const backgroundColors = labels.map((_, index) => createUniqueGradient(index));

            // Update the chart
            if (chart) {
                chart.destroy(); // Destroy previous chart instance
            }

            const ctx = document.getElementById('barchartCanvas').getContext('2d');
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Requested Goods',
                        data: reqNums,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color),
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Make the chart horizontal
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `Requests: ${tooltipItem.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false,
                            },
                            ticks: {
                                beginAtZero: true,
                            }
                        },
                        y: {
                            grid: {
                                display: false,
                            }
                        }
                    }
                }
            });

            // Hover effect - expanding the bars and shine effect
            const bars = document.querySelectorAll('#barchartCanvas .bar');
            bars.forEach(bar => {
                bar.addEventListener('mouseover', function() {
                    this.style.transform = 'scale(1.1)';
                    this.style.boxShadow = '0 0 15px rgba(255, 255, 255, 0.6)'; // Shine effect
                });

                bar.addEventListener('mouseout', function() {
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = ''; // Remove shine effect
                });
            });
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Function to create a unique gradient for each bar using #B81701 and #1a548d
function createUniqueGradient(index) {
    const ctx = document.getElementById('barchartCanvas').getContext('2d');

    // Create a new linear gradient with different angles and stops for variety
    const gradient = ctx.createLinearGradient(0, 0, 1, 0); // Horizontal gradient by default

    // Add multiple stops to create a more complex gradient effect
    switch (index % 4) {
        case 0:
            // First variant: Gradient from red (#B81701) to blue (#1a548d) with a touch of yellow
            gradient.addColorStop(0, '#B81701'); // Red
            gradient.addColorStop(0.5, '#FFAA00'); // Yellow
            gradient.addColorStop(1, '#1a548d'); // Blue
            break;
        case 1:
            // Second variant: Gradient from blue (#1a548d) to red (#B81701) with a purple touch
            gradient.addColorStop(0, '#1a548d'); // Blue
            gradient.addColorStop(0.5, '#9B1D47'); // Purple
            gradient.addColorStop(1, '#B81701'); // Red
            break;
        case 2:
            // Third variant: Gradient with a subtle red-to-blue wave
            gradient.addColorStop(0, '#B81701'); // Red
            gradient.addColorStop(0.4, '#FF6347'); // Tomato red
            gradient.addColorStop(0.6, '#1a548d'); // Blue
            gradient.addColorStop(1, '#5C80B0'); // Light blue
            break;
        case 3:
            // Fourth variant: A more mixed and dramatic gradient between red and blue
            gradient.addColorStop(0, '#B81701'); // Red
            gradient.addColorStop(0.3, '#DC143C'); // Crimson
            gradient.addColorStop(0.7, '#1a548d'); // Blue
            gradient.addColorStop(1, '#4169E1'); // Royal blue
            break;
        default:
            // Default case: Simple red to blue
            gradient.addColorStop(0, '#B81701'); // Red
            gradient.addColorStop(1, '#1a548d'); // Blue
    }

    return gradient;
}

</script>


<script>
    
    let chart2 = null;

// Colors for the lines - using gradients
const colors2 = ['#B81701', '#1a548d']; // The two primary colors for the gradient

// Data fetched from PHP embedded into JavaScript
const data = <?php echo json_encode($data); ?>;

const labels = [...new Set(data.map(item => item.Date))]; // Unique dates for X-axis
const goodsNames = [...new Set(data.map(item => item.GoodsName))]; // Unique goods names

// Prepare datasets for each GoodsName with gradient
const datasets = goodsNames.map((goodsName, index) => {
    const dataPoints = labels.map(date => {
        const entry = data.find(item => item.Date === date && item.GoodsName === goodsName);
        return entry ? entry.ReqNum : 0; // Use 0 if no data for that date
    });

    const gradient = createGradient(index);

    return {
        label: goodsName,
        data: dataPoints,
        borderColor: gradient, // Apply gradient to the border
        backgroundColor: gradient + '33', // Transparent fill using gradient
        tension: 0.4, // Smooth curves
    };
});

// Function to create a unique gradient for each line
function createGradient(index) {
    const ctx = document.getElementById('goodsChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 1, 0); // Horizontal gradient by default

    // Add multiple stops to create a more complex gradient effect
    switch (index % 2) {
        case 0:
            gradient.addColorStop(0, '#B81701'); // Red
            gradient.addColorStop(1, '#1a548d'); // Blue
            break;
        case 1:
            gradient.addColorStop(0, '#1a548d'); // Blue
            gradient.addColorStop(1, '#B81701'); // Red
            break;
        default:
            gradient.addColorStop(0, '#B81701'); // Red
            gradient.addColorStop(1, '#1a548d'); // Blue
    }

    return gradient;
}

// Update the chart
if (chart2) {
    chart2.destroy();
}

const ctx = document.getElementById('goodsChart').getContext('2d');
chart2 = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: datasets,
    },
    options: {
        scales: {
            x: {
                title: { display: true, text: 'Date' },
                grid: { display: false }, // Disable X-axis grid lines
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Number of Requests' },
                grid: { display: false }, // Disable Y-axis grid lines
            }
        },
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Evacuee Request Over the Last 7 Days', // Title for the chart
                font: {
                    size: 18, // Adjust font size
                },
                padding: {
                    top: 10,
                    bottom: 30
                },
            },
            tooltip: {
                callbacks: {
                    label: context => `${context.dataset.label}: ${context.raw}`
                }
            }
        }
    }
});

// Function to apply the filter
function applyFilter() {
    const evID = document.getElementById('evID').value;
    const url = evID ? `?EvacID=${evID}` : '';
    window.location.href = url; // Redirect to the page with the selected EvacID filter
}
    </script>


<script>
// Pass data to JavaScript
var evacNames = <?php echo json_encode($evac_names); ?>;
var evacuees = <?php echo json_encode($evacuees); ?>;
var evacCapacity = <?php echo json_encode($evac_capacity); ?>;

// Create the chart
var ctxs = document.getElementById('columnChart').getContext('2d');
var columnChart = new Chart(ctxs, {
    type: 'bar',
    data: {
        labels: evacNames,  // Evacuation area names
        datasets: [
            {
                label: 'Capacity',
                data: evacCapacity,  // Evacuation capacity values
                backgroundColor: '#1a548d', // Blue for capacity
                borderColor: '#1a548d', // Border same as capacity color
                borderWidth: 2,
                borderRadius: 5,  // Rounded corners
                hoverBackgroundColor: '#15628f',  // Darker blue on hover
                hoverBorderColor: '#1a548d',  // Darker blue border on hover
                hoverBorderWidth: 3, // Thicker border on hover
                hoverRadius: 10,  // Increase hover radius for interactivity
            },
            {
                label: 'Actual Residents',
                data: evacuees,  // Actual evacuees data
                backgroundColor: '#B81701', // Red for actual residents
                borderColor: '#B81701', // Border same as red color
                borderWidth: 2,
                borderRadius: 5,  // Rounded corners
                hoverBackgroundColor: '#9d1400',  // Darker red on hover
                hoverBorderColor: '#B81701',  // Darker red border on hover
                hoverBorderWidth: 3, // Thicker border on hover
                hoverRadius: 10,  // Increase hover radius for interactivity
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                ticks: {
                    font: {
                        size: 12,  // Font size for x-axis labels
                        weight: 'bold',  // Make labels bold
                    }
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    font: {
                        size: 12,  // Font size for y-axis labels
                        weight: 'bold',  // Make labels bold
                    },
                    stepSize: 100,  // Adjust the step size for better readability
                    padding: 15,  // Space between y-axis labels and chart
                }
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Evacuation Area Capacity vs. Number of Evacuees',  // Chart Title
                position: 'top',  // Positioning the title at the top of the chart area
                font: {
                    size: 16,  // Font size of the title
                    weight: 'bold',
                    family: 'Arial, sans-serif',  // Font family for title
                },
                padding: {
                    top: 20,  // Space from the chart area to the title
                    bottom: 10  // Space from the title to the chart
                }
            }
        },
        layout: {
            padding: {
                left: 15,
                right: 15,
                top: 15,
                bottom: 15,  // Add padding for neatness
            }
        }
    }
});


</script>

<script>
    function filterData() {
        const EID = document.getElementById('EID').value;
        const url = window.location.href.split('?')[0] + '?EID=' + EID;
        window.location.href = url;
    }

    document.addEventListener("DOMContentLoaded", function () {
    // Data from PHP
    const data = <?php echo $jsonData; ?>;
    const maleData = data.male;
    const femaleData = data.female;

    // Calculate total counts for scoreboards
    const totalMale = maleData.reduce((a, b) => a + b, 0);
    const totalFemale = femaleData.reduce((a, b) => a + b, 0);

    // Update scoreboards
    document.getElementById('totalMale').textContent = totalMale;
    document.getElementById('totalFemale').textContent = totalFemale;

    // Create gradient dynamically
    const ctx = document.getElementById('ageDistributionChart').getContext('2d');

    function createGradient(color1, color2) {
        const gradient = ctx.createLinearGradient(0, 0, 200, 200);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    }

    let gradients = [
        createGradient('#1a548d', '#365f97'), // Male - Dark blue to medium blue
        createGradient('#1a548d', '#4f79b8'),
        createGradient('#1a548d', '#2a4f85'),
        createGradient('#1a548d', '#203f6f'),
        createGradient('#B81701', '#d13a29'), // Female - Dark red to medium red
        createGradient('#B81701', '#e04b3a'),
        createGradient('#B81701', '#c82f1e'),
        createGradient('#B81701', '#a32317')
    ];

    function loadChart() {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Kids (Male)', 'Teenager (Male)', 'Adult (Male)', 'Senior (Male)',
                    'Kids (Female)', 'Teenager (Female)', 'Adult (Female)', 'Senior (Female)'
                ],
                datasets: [{
                    label: 'Age Distribution by Gender',
                    data: maleData.concat(femaleData),
                    backgroundColor: gradients,
                    borderColor: '#fff',
                    borderWidth: 1,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                cutoutPercentage: 70,
                animations: {
                    // This creates the "drawing" effect for the chart
                    animateRotate: true, // Enables rotation effect during loading
                    animateScale: true,  // Enables scale animation for the chart
                    duration: 2000,      // Animation duration (2 seconds)
                    easing: 'easeOutCubic', // Smooth easing for the drawing effect
                    onProgress: function(animation) {
                        const rotationAngle = animation.currentStep / animation.numSteps * 360; // Rotating the chart during load
                        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height); // Clears the canvas on each frame
                        ctx.save();
                        ctx.translate(ctx.canvas.width / 2, ctx.canvas.height / 2);
                        ctx.rotate(rotationAngle * Math.PI / 180); // Rotate by the angle
                        ctx.translate(-ctx.canvas.width / 2, -ctx.canvas.height / 2);
                        // Draw the chart on each frame
                        this.chart.draw();
                        ctx.restore();
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const ageCategory = tooltipItem.label.split('(')[0].trim();
                                return ageCategory + ': ' + tooltipItem.raw;
                            }
                        }
                    },
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Age Distribution Based on Sex',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        color: '#333'
                    }
                }
            }
        });
    }

    // Initial load
    loadChart();

    // Reload chart on window update
    window.addEventListener('load', loadChart);
});

</script>



</body>

</html>
