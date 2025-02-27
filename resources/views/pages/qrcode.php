
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
    <h2>QR Code Inline (Data URI)</h2>
    <img src="<?php echo $dataUri; ?>" alt="QR Code">
</body>
</html>

<script src="https://unpkg.com/html5-qrcode"></script> 

<div id="reader" style="width: 300px;"></div>

<p><strong>Scanned Result:</strong> <span id="result">Waiting...</span></p>

<button id="toggleScanner">Scanner</button>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById("result").innerText = decodedText;
    }

    function onScanError(errorMessage) {
        console.warn(errorMessage);
    }

    let html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" }, 
        {
            fps: 10, 
            qrbox: 250 
        },
        onScanSuccess,
        onScanError
    );

   
</script>