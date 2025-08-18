/**
 * Manual QR Input Fix for Scanner
 * Handles manual QR code input and item lookup functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Manual QR input elements
    const manualQrInput = document.getElementById('manualQrInput');
    const manualScanBtn = document.getElementById('manualScanBtn');
    const itemsTable = document.getElementById('itemsTable');
    
    if (!manualQrInput || !manualScanBtn || !itemsTable) {
        console.warn('Manual QR input elements not found');
        return;
    }
    
    // Handle manual QR input
    function handleManualQrInput() {
        const qrCode = manualQrInput.value.trim();
        if (!qrCode) {
            alert('Please enter a QR code');
            return;
        }
        
        // Find item by QR code
        findItemByQrCode(qrCode);
    }
    
    // Find item by QR code
    function findItemByQrCode(qrCode) {
        const rows = itemsTable.querySelectorAll('tbody tr');
        let found = false;
        
        // Clear previous highlights
        rows.forEach(row => {
            row.classList.remove('highlighted');
        });
        
        // Search for matching QR code
        rows.forEach(row => {
            const rowQrCode = row.dataset.qrCode;
            if (rowQrCode === qrCode) {
                row.classList.add('highlighted');
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                found = true;
                
                // Remove highlight after 3 seconds
                setTimeout(() => {
                    row.classList.remove('highlighted');
                }, 3000);
                
                // Update scanned items count
                updateScannedCount();
            }
        });
        
        if (!found) {
            alert('Item not found with QR code: ' + qrCode);
        }
    }
    
    // Update scanned items count
    function updateScannedCount() {
        const scannedCount = document.getElementById('scannedItems');
        if (scannedCount) {
            const currentCount = parseInt(scannedCount.textContent) || 0;
            scannedCount.textContent = currentCount + 1;
        }
    }
    
    // Event listeners
    manualScanBtn.addEventListener('click', handleManualQrInput);
    
    manualQrInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleManualQrInput();
        }
    });
    
    // Add AJAX form submission for manual QR
    function submitManualQr(qrCode) {
        fetch('/scanner/manual-qr', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessMessage(data.message);
                findItemByQrCode(qrCode);
            } else {
                showErrorMessage(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Failed to process QR code');
        });
    }
    
    // Success/Error message functions
    function showSuccessMessage(message) {
        const successDiv = document.getElementById('dynamicSuccessMessage');
        if (successDiv) {
            const messageText = document.getElementById('successMessageText');
            messageText.textContent = message;
            successDiv.style.display = 'block';
            
            setTimeout(() => {
                successDiv.style.display = 'none';
            }, 3000);
        }
    }
    
    function showErrorMessage(message) {
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            const errorText = document.getElementById('errorText');
            errorText.textContent = message;
            errorDiv.style.display = 'block';
            
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }
    }
});
