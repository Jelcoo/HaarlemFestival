<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
</head>
<body>
    <h2>QR Code Inline (Data URI)</h2>
    <img src="<?php echo $dataUri; ?>" alt="QR Code">

    <button id="toggleScanner">Open Scanner</button>
    <div id="reader" style="width: 300px; display: none;"></div>
    <p><strong>Scanned Result:</strong> <span id="result">Waiting...</span></p>

    <script src="https://unpkg.com/html5-qrcode"></script> 
    <script>
        let html5QrCode = new Html5Qrcode("reader");
        let scannerRunning = false;

        function onScanSuccess(decodedText) {
            document.getElementById("result").innerText = decodedText;
        }

        function onScanError(errorMessage) {
            console.warn(errorMessage);
        }

        document.getElementById("toggleScanner").addEventListener("click", function() {
            let reader = document.getElementById("reader");
            if (!scannerRunning) {
                reader.style.display = "block";
                html5QrCode.start(
                    { facingMode: "environment" }, 
                    { fps: 10, qrbox: 250 },
                    onScanSuccess,
                    onScanError
                );
                this.innerText = "Close Scanner";
            } else {
                html5QrCode.stop().then(() => {
                    reader.style.display = "none";
                    this.innerText = "Open Scanner";
                }).catch(err => console.error("Stop failed: ", err));
            }
            scannerRunning = !scannerRunning;
        });
    </script>
</body>
</html>
