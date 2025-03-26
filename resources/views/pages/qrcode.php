<script src="https://unpkg.com/html5-qrcode"></script> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<h1>Select at what event you are</h1>
<?php

    //echo "<img src='{$qr}' alt='QR Code' style='width:150px; height:150px;'>";
    
?>


<div class="container mt-4 d-flex gap-3">
    <div class="mb-3">
        <label for="dance-events" class="form-label">Dance Events:</label>
        <select id="dance-events" class="form-select event-select" data-event-type="dance">
            <?php foreach ($allDanceEvents as $event): ?>
                <option value="<?= $event['id'] ?>">
                    <?= $event['info'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-success mt-2 choose-button">Choose</button>
    </div>

    <div class="mb-3">
        <label for="yummy-events" class="form-label">Yummy Events:</label>
        <select id="yummy-events" class="form-select event-select" data-event-type="yummy">
            <?php foreach ($allYummyEvents as $event): ?>
                <option value="<?= $event['id'] ?>">
                    <?= $event['info'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-warning mt-2 choose-button">Choose</button>
    </div>

    <div class="mb-3">
        <label for="history-events" class="form-label">History Events:</label>
        <select id="history-events" class="form-select event-select" data-event-type="history">
            <?php foreach ($allHistoryEvents as $event): ?>
                <option value="<?= $event['id'] ?>">
                    <?= $event['info'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-info mt-2 choose-button">Choose</button>
    </div>
</div>

<div class="container d-flex flex-column align-items-center justify-content-center vh-100 text-center">
    <div id="reader" class="border rounded p-3 mb-3" style="width: 300px; background-color: #f0f0f0; display: block;">Not Scanning...</div>
    <p class="fw-bold">Scanned Result: <span id="result">Waiting...</span></p>

    <button id="scanButton" class="btn btn-primary" disabled>Start Scanner</button>
</div>

<script>
    let selectedEvent = null;

    document.querySelectorAll(".choose-button").forEach(button => {
        button.addEventListener("click", function () {
            const selectElement = this.previousElementSibling;
            selectedEvent = {
                type: selectElement.getAttribute("data-event-type"),
                id: selectElement.value
            };
            document.getElementById("scanButton").removeAttribute("disabled");
        });
    });

    function onScanSuccess(decodedText) {
        if (selectedEvent) {
            const qrcode = `${decodedText}|${selectedEvent.type}|${selectedEvent.id}`;
            document.getElementById("result").innerText = `Qr found, please wait...`;
            window.location.href = `/scannedqrcode?qr=${encodeURIComponent(qrcode)}`;
        } else {
            alert("Please select an event before scanning.");
        }
    }

    function onScanError(errorMessage) {
        console.warn(errorMessage);
    }

    const html5QrCode = new Html5Qrcode("reader");
    let isScannerActive = false;

    document.getElementById("scanButton").addEventListener("click", function () {
        const readerElement = document.getElementById("reader");

        if (!isScannerActive) {
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                onScanSuccess,
                onScanError
            ).then(() => {
                readerElement.style.backgroundColor = "transparent";
                readerElement.style.textAlign = "left";
                readerElement.style.lineHeight = "normal";
                this.innerText = "Stop Scanner";
                isScannerActive = true;
            }).catch(err => {
                console.error("Error starting scanner: ", err);
            });
        } else {
            html5QrCode.stop().then(() => {
                readerElement.style.backgroundColor = "#f0f0f0";
                readerElement.innerText = "Not Scanning...";
                readerElement.style.textAlign = "center";
                readerElement.style.lineHeight = "300px";
                this.innerText = "Start Scanner";
                isScannerActive = false;
            }).catch(err => {
                console.error("Error stopping scanner: ", err);
            });
        }
    });
</script>
