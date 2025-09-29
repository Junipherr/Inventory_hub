<!-- Sample page for qr code -->
<div id="reader" style="width:300px;"></div>
<p id="result">Scan result will show here</p>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('result').innerText = `Scanned: ${decodedText}`;

        fetch('/qr/store-scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code: decodedText })
        });
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 10,
            qrbox: 250
        },
        onScanSuccess
    );
</script>
