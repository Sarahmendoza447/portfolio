<?php
// Check if evacId is set in the URL query string
if (isset($_GET['evacId'])) {
    $evacId = $_GET['evacId'];


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script> <!-- JSQR library for scanning -->
    <link rel="stylesheet" href="scanner.css">
</head>
<body>

<div class="container" style="margin-top: 50px;">
    <h1 class="text-center text-white">Scan QR Code</h1>
    <div class="d-flex justify-content-center">
        <button class="btn btn-primary" id="startScannerBtn">Start Scanning</button>
    </div>

    <div class="text-center" style="margin-top: 20px;">
        <video id="qrScanner" width="300" height="200" style="border: 1px solid black; display:none;"></video>
    </div>

    <div id="scanResult" class="text-center" style="margin-top: 20px;"></div>

    <!-- Add a Stop Scanning button -->
    <button class="btn btn-danger" id="stopScannerBtn" style="display:none; margin-top: 20px;">Stop Scanning</button>
</div>

<script>
    const video = document.getElementById('qrScanner');
    const scanResult = document.getElementById('scanResult');
    const startScannerBtn = document.getElementById('startScannerBtn');
    const stopScannerBtn = document.getElementById('stopScannerBtn');

    startScannerBtn.addEventListener('click', function () {
        startScannerBtn.style.display = 'none';
        video.style.display = 'block';
        startCamera();
    });

    stopScannerBtn.addEventListener('click', function () {
        stopCamera();
    });

    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
            .then(function (stream) {
                video.srcObject = stream;
                video.setAttribute("playsinline", true);
                video.play();
                requestAnimationFrame(scanQRCode);
                stopScannerBtn.style.display = 'block';
            })
            .catch(function (err) {
                console.error("Error accessing webcam: ", err);
            });
    }

    function scanQRCode() {
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code) {
                scanResult.innerHTML = `<strong>You can only use a Qr code once </strong>`;
                processScannedData(code.data);  // Pass the scanned data to the server
                video.srcObject.getTracks().forEach(track => track.stop());
            }
        }
        requestAnimationFrame(scanQRCode);
    }

    function processScannedData(qrData) {
        // Log the data being scanned to verify
        console.log("Scanned QR Data:", qrData);

        // Send data to the server
        const formData = new FormData();
        formData.append('qrData', qrData);  // Just send the raw scanned data (text)

        fetch('qr_sacnner.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Server Response:", data);  // Log the response from the backend
                if (data.success) {
                    console.log("QR code matched:", data);
                } else {
                    console.error("Error:", data.message);
                }
            })
            .catch((error) => console.error("Error in response:", error));
    }

    function stopCamera() {
        // Stop all media tracks
        const stream = video.srcObject;
        const tracks = stream.getTracks();
        tracks.forEach(track => track.stop());

        video.srcObject = null;
        video.style.display = 'none';
        stopScannerBtn.style.display = 'none';
        startScannerBtn.style.display = 'block';
    }
</script>
</body>
</html>
