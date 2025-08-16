// QR Code functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('QR Code functionality initialized');
    
    // Ensure all QR code canvases have proper dimensions and styling
    document.querySelectorAll('canvas[id^="qrcode-"]').forEach(canvas => {
        canvas.style.width = '150px';
        canvas.style.height = '150px';
        canvas.style.display = 'none'; // Hide canvas
        canvas.style.margin = '0 auto';
        canvas.style.border = '1px solid #dee2e6';
        canvas.style.borderRadius = '0.375rem';
    });
    
    // Function to generate QR code for a specific canvas
    function generateQRCode(canvasId, qrData) {
        const canvas = document.getElementById(canvasId);
        const imgTag = document.createElement('img');
        imgTag.style.display = 'block';
        imgTag.style.margin = '0 auto';
        imgTag.style.maxWidth = '150px';
        imgTag.style.height = 'auto';
        
        if (!canvas) {
            console.error(`Canvas with ID ${canvasId} not found`);
            return;
        }
        
        if (!qrData || qrData.trim() === '') {
            console.warn(`No QR data provided for canvas ${canvasId}`);
            return;
        }
        
        console.log(`Generating QR code for ${canvasId} with data:`, qrData);
        
        // Generate QR code as base64 image
        QRCode.toDataURL(qrData, {
            width: 150,
            margin: 2,
            color: {
                dark: '#000000',
                light: '#FFFFFF'
            },
            errorCorrectionLevel: 'M'
        }, function(error, url) {
            if (error) {
                console.error(`QRCode generation error for ${canvasId}:`, error);
                // Fallback: display error message
                canvas.innerHTML = '<div class="text-center text-danger p-3">Error generating QR code</div>';
            } else {
                console.log(`QR code generated successfully for ${canvasId}`);
                imgTag.src = url; // Set the base64 image as the source
                canvas.parentNode.appendChild(imgTag); // Append the image to the modal
            }
        });
    }
    
    // Generate QR codes for all existing canvases
    document.querySelectorAll('canvas[id^="qrcode-"]').forEach(canvas => {
        const canvasId = canvas.id;
        const modal = canvas.closest('.modal');
        if (modal) {
            const qrData = modal.getAttribute('data-qr');
            if (qrData) {
                generateQRCode(canvasId, qrData);
            }
        }
    });
    
    // Handle modal show events for dynamic QR code generation
    document.addEventListener('show.bs.modal', function(event) {
        const modal = event.target;
        const qrData = modal.getAttribute('data-qr');
        
        if (qrData) {
            const canvas = modal.querySelector('canvas[id^="qrcode-"]');
            if (canvas) {
                generateQRCode(canvas.id, qrData);
            }
        }
    });
    
    // Debug function to test QR code generation
    window.testQRGeneration = function(canvasId, testData) {
        generateQRCode(canvasId, testData);
    };
});
