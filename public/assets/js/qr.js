document.getElementById('qrForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var formData = new FormData(this); // Create a FormData object

    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Indicate that this is an AJAX request
        }
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('qrCodeDisplay').innerHTML = data; // Update the QR code display
    })
    .catch(error => console.error('Error:', error));
});

