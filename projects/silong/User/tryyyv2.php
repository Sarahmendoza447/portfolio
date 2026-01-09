<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evacuation Centers</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
    <style>
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
            margin: 5% auto;
            padding: 20px;
            width: 90%;
            max-width: 800px;
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
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        #details-content {
            position: relative;
            margin: 10% auto;
            padding: 20px;
            width: 90%;
            max-width: 400px;
            background: #fff;
            border-radius: 10px;
        }
        #close-details-container {
            position: absolute;
            top: 10px;
            right: 15px;
            cursor: pointer;
            font-size: 20px;
        }
        #map { height: 500px; width: 100%; }
        #details-container-content .img {

        }
    </style>
</head>
<body>

<button onclick="openMapModal()">Show Evacuation Centers Map</button>

<div id="map-container">
    <div id="map-content">
        <span id="close-map-container" onclick="closeMapModal()">×</span>
        <div id="map"></div>
    </div>
</div>

<!-- Details Modal -->
<div id="details-container">
    <div id="details-content">
        <span id="close-details-container" onclick="closeDetailsModal()">×</span>
        <div id="details-info"></div>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
<script>
    let map;
    let markers;
    let mapInitialized = false;

    // Open map modal and initialize map if not already done
    function openMapModal() {
        document.getElementById('map-container').style.display = 'block';

        if (!mapInitialized) {
            initializeMap();
            mapInitialized = true;
        }

        // Ensure map is rendered correctly
        setTimeout(() => map.invalidateSize(), 100);
    }

    // Close map modal
    function closeMapModal() {
        document.getElementById('map-container').style.display = 'none';
    }

    // Initialize map
    function initializeMap() {
        map = L.map('map').setView([14.0689, 120.6286], 14); // Default center
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        markers = L.markerClusterGroup();
        fetchEvacuationCenters();
    }

    function fetchEvacuationCenters() {
        fetch("bphp.php")
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data)) {
                    alert("Unexpected data format. Please check the server response.");
                    return;
                }

                data.forEach(center => {
                    const { evac_name, evac_lat, evac_lng, evac_capacity, evacuees, photo_base64 } = center;

                    if (typeof evac_lat === "number" && typeof evac_lng === "number") {
                        const marker = L.marker([evac_lat, evac_lng]);
                        marker.on('click', () => {
                            const remainingCapacity = evac_capacity - evacuees;

                            showDetailsModal(`
                            <strong>Name:</strong> ${evac_name}<br>
                            <strong>Capacity:</strong> ${evac_capacity}<br>
                            <strong>Evacuees:</strong> ${evacuees}<br>
                            <strong>Remaining Capacity:</strong> ${remainingCapacity}<br>
                            <img src="data:image/jpeg;base64,${photo_base64}" alt="${evac_name}" style="max-width: 100%; margin-top: 10px;">
                        `);
                        });
                        markers.addLayer(marker);
                    }
                });

                map.addLayer(markers);
            })
            .catch(error => {
                console.error("Error fetching evacuation centers:", error);
                alert("An error occurred while loading evacuation centers.");
            });
    }



    // Show details modal
    function showDetailsModal(content) {
        const detailsContainer = document.getElementById('details-container');
        document.getElementById('details-info').innerHTML = content;
        detailsContainer.style.display = 'block';
    }

    // Close details modal
    function closeDetailsModal() {
        document.getElementById('details-container').style.display = 'none';
    }
</script>
</body>
</html>
