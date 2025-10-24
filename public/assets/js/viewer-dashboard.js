// Viewer Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchItems');
    const itemCards = document.querySelectorAll('.item-card');
    const totalItemsSpan = document.getElementById('totalItems');

    // Status legend modal
    const showLegendBtn = document.getElementById('showLegendBtn');
    const statusLegendModal = new bootstrap.Modal(document.getElementById('statusLegendModal'));
    
    // Item details modal
    const itemInfoModal = new bootstrap.Modal(document.getElementById('itemInfoModal'));
    const modalItemName = document.getElementById('modalItemName');
    const modalRoom = document.getElementById('modalRoom');
    const modalCategory = document.getElementById('modalCategory');
    const modalQuantity = document.getElementById('modalQuantity');
    const modalStatus = document.getElementById('modalStatus');
    const modalDescription = document.getElementById('modalDescription');
    const modalQRCode = document.getElementById('modalQRCode');
    const qrcodeContainer = document.getElementById('qrcode-container');

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let visibleCount = 0;

            itemCards.forEach(card => {
                const itemName = card.querySelector('.card-header h6').textContent.toLowerCase();
                const category = card.querySelector('.card-body p:nth-child(1)').textContent.toLowerCase();
                const room = card.querySelector('.card-body p:nth-child(2)').textContent.toLowerCase();
                const description = card.querySelector('.card-body p:nth-child(3)').textContent.toLowerCase();

                const matches = itemName.includes(searchTerm) ||
                               category.includes(searchTerm) ||
                               room.includes(searchTerm) ||
                               description.includes(searchTerm);

                if (matches) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (totalItemsSpan) {
                totalItemsSpan.textContent = visibleCount;
            }
        });
    }

    // Show status legend
    if (showLegendBtn) {
        showLegendBtn.addEventListener('click', function() {
            statusLegendModal.show();
        });
    }

    // View item details
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            const itemName = this.dataset.itemName;
            const room = this.dataset.room;
            const category = this.dataset.category;
            const quantity = this.dataset.quantity;
            const description = this.dataset.description;
            const qrCode = this.dataset.qr;

            modalItemName.textContent = itemName;
            modalRoom.textContent = room;
            modalCategory.textContent = category;
            modalQuantity.textContent = quantity;
            modalDescription.textContent = description || 'No description available';

            // Update QR code dynamically
            updateModalQRCode(qrCode, itemName);

            itemInfoModal.show();
        });
    });

    // Function to update QR code in modal
    function updateModalQRCode(qrCode, itemName) {
        const qrImage = document.getElementById('modalQRImage');
        const qrLoading = document.getElementById('qrLoading');
        const modalQRText = document.getElementById('modalQRText');

        if (qrCode && qrCode !== 'N/A') {
            // Show loading
            qrLoading.style.display = 'block';
            qrImage.style.display = 'none';
            modalQRText.textContent = qrCode;

            // Generate QR code via AJAX
            fetch(`/inventory/qrcode/${encodeURIComponent(qrCode)}`)
                .then(response => response.text())
                .then(svgText => {
                    const svgBlob = new Blob([svgText], { type: 'image/svg+xml' });
                    const url = URL.createObjectURL(svgBlob);
                    qrImage.src = url;
                    qrImage.alt = `QR Code for ${itemName}`;
                    qrImage.style.display = 'block';
                    qrLoading.style.display = 'none';
                })
                .catch(error => {
                    console.error('Error loading QR code:', error);
                    qrImage.src = '';
                    qrImage.alt = 'Error loading QR Code';
                    qrImage.style.display = 'none';
                    qrLoading.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error loading QR Code';
                    qrLoading.style.display = 'block';
                });
        } else {
            qrImage.src = '';
            qrImage.alt = 'No QR Code available';
            qrImage.style.display = 'none';
            qrLoading.innerHTML = '<i class="fas fa-info-circle"></i> No QR Code available';
            qrLoading.style.display = 'block';
            modalQRText.textContent = 'N/A';
        }
    }

    // Form submission for status updates
    const viewerForm = document.getElementById('viewerForm');
    if (viewerForm) {
        viewerForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const errorDiv = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            
            // Hide any previous error messages
            errorDiv.style.display = 'none';

            fetch(e.target.action, {
                method: 'POST',
                body: new FormData(e.target),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message || 'Changes saved successfully!');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                } else {
                    showErrorMessage(data.message || 'Error saving changes. Please try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error saving changes. Please check your connection and try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
            });
        });
    }

    // Success message
    let successTimeout;

    function showSuccessMessage(message) {
        const successDiv = document.getElementById('dynamicSuccessMessage');
        const messageText = document.getElementById('successMessageText');
        messageText.textContent = message;
        successDiv.style.display = 'block';

        // Clear any previous timeout to prevent stacking
        if (successTimeout) {
            clearTimeout(successTimeout);
        }
        successTimeout = setTimeout(() => {
            successDiv.style.display = 'none';
        }, 3000);
    }

    // Error message
    function showErrorMessage(message) {
        const errorDiv = document.getElementById('errorMessage');
        const errorText = document.getElementById('errorText');
        errorText.textContent = message;
        errorDiv.style.display = 'block';
        
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }

    // Table sorting
    document.querySelectorAll('th').forEach(header => {
        header.addEventListener('click', function() {
            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const columnIndex = Array.from(this.parentNode.children).indexOf(this);
            const isAscending = this.classList.contains('ascending');

            // Remove sorting classes
            table.querySelectorAll('th').forEach(th => {
                th.classList.remove('ascending', 'descending');
            });

            // Add appropriate class
            this.classList.add(isAscending ? 'descending' : 'ascending');

            // Sort rows
            rows.sort((a, b) => {
                const aValue = a.children[columnIndex].textContent.trim();
                const bValue = b.children[columnIndex].textContent.trim();
                
                if (isAscending) {
                    return bValue.localeCompare(aValue);
                } else {
                    return aValue.localeCompare(bValue);
                }
            });

            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        });
    });

    // Add sorting indicators
    document.querySelectorAll('th').forEach(header => {
        header.style.cursor = 'pointer';
        header.title = 'Click to sort';
    });

    // Print functionality
    function printTable() {
        const printWindow = window.open('', '_blank');
        const tableContent = document.getElementById('itemsTable').outerHTML;
        
        printWindow.document.write(`
            <html>
                <head>
                    <title>My Inventory - ${new Date().toLocaleDateString()}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
                        th { background-color: #f2f2f2; }
                    </style>
                </head>
                <body>
                    <h1>My Inventory - ${new Date().toLocaleDateString()}</h1>
                    <p>Room: ${document.querySelector('.header-stats strong').textContent}</p>
                    ${tableContent}
                </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.print();
    }

    // Add print button functionality
    const printBtn = document.createElement('button');
    printBtn.className = 'btn btn-outline-secondary btn-sm ms-2';
    printBtn.innerHTML = '<i class="fas fa-print"></i> Print';
    printBtn.addEventListener('click', printTable);
    
    const itemsHeader = document.querySelector('.items-header');
    if (itemsHeader) {
        itemsHeader.querySelector('.items-controls').appendChild(printBtn);
    }

    // QR Scanner functionality for viewer dashboard
    let viewerStream = null;
    let viewerScanning = false;

    const startViewerScannerBtn = document.getElementById('startViewerScanner');
    const stopViewerScannerBtn = document.getElementById('stopViewerScanner');
    const viewerScannerVideo = document.getElementById('viewerScannerVideo');
    const viewerScannerCanvas = document.getElementById('viewerScannerCanvas');
    const viewerScannerPlaceholder = document.getElementById('viewerScannerPlaceholder');
    const viewerScanResult = document.getElementById('viewerScanResult');
    const viewerScannedCode = document.getElementById('viewerScannedCode');
    const findViewerItemBtn = document.getElementById('findViewerItem');
    const viewerManualQrInput = document.getElementById('viewerManualQrInput');
    const viewerManualScanBtn = document.getElementById('viewerManualScanBtn');

    // Start QR scanner
    if (startViewerScannerBtn) {
        startViewerScannerBtn.addEventListener('click', startViewerQRScanner);
    }

    // Stop QR scanner
    if (stopViewerScannerBtn) {
        stopViewerScannerBtn.addEventListener('click', stopViewerQRScanner);
    }

    // Manual QR lookup
    if (viewerManualScanBtn) {
        viewerManualScanBtn.addEventListener('click', handleManualViewerQRScan);
    }

    // Handle Enter key in manual input
    if (viewerManualQrInput) {
        viewerManualQrInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleManualViewerQRScan();
            }
        });
    }

    // Find item in table after scan
    if (findViewerItemBtn) {
        findViewerItemBtn.addEventListener('click', findScannedItemInViewer);
    }

    function startViewerQRScanner() {
        if (viewerScanning) return;

        const constraints = {
            video: {
                facingMode: 'environment',
                width: { ideal: 640 },
                height: { ideal: 480 }
            }
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                viewerStream = stream;
                viewerScanning = true;
                
                viewerScannerVideo.srcObject = stream;
                viewerScannerVideo.style.display = 'block';
                viewerScannerPlaceholder.style.display = 'none';
                
                startViewerScannerBtn.style.display = 'none';
                stopViewerScannerBtn.style.display = 'inline-block';
                
                // Start scanning
                scanViewerQRCode();
            })
            .catch(function(err) {
                console.error("Error accessing camera: ", err);
                showErrorMessage("Error accessing camera. Please ensure camera permissions are granted.");
            });
    }

    function stopViewerQRScanner() {
        if (viewerStream) {
            viewerStream.getTracks().forEach(track => track.stop());
            viewerStream = null;
        }
        
        viewerScanning = false;
        viewerScannerVideo.style.display = 'none';
        viewerScannerPlaceholder.style.display = 'block';
        
        startViewerScannerBtn.style.display = 'inline-block';
        stopViewerScannerBtn.style.display = 'none';
        
        viewerScanResult.style.display = 'none';
    }

    function scanViewerQRCode() {
        if (!viewerScanning) return;

        const canvas = viewerScannerCanvas;
        const context = canvas.getContext('2d');
        
        if (viewerScannerVideo.videoWidth > 0 && viewerScannerVideo.videoHeight > 0) {
            canvas.width = viewerScannerVideo.videoWidth;
            canvas.height = viewerScannerVideo.videoHeight;
            context.drawImage(viewerScannerVideo, 0, 0, canvas.width, canvas.height);
            
            try {
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                
                if (code) {
                    handleViewerQRCodeDetected(code.data);
                    return;
                }
            } catch (e) {
                console.error("QR scan error:", e);
            }
        }
        
        // Continue scanning
        if (viewerScanning) {
            requestAnimationFrame(scanViewerQRCode);
        }
    }

    function handleViewerQRCodeDetected(qrCode) {
        viewerScannedCode.textContent = qrCode;
        viewerScanResult.style.display = 'block';
        
        // Auto-find the item
        findScannedItemInViewer(qrCode);
        
        // Stop scanner after successful scan
        setTimeout(() => {
            stopViewerQRScanner();
        }, 2000);
    }

    function handleManualViewerQRScan() {
        const qrCode = viewerManualQrInput.value.trim();
        if (!qrCode) {
            showErrorMessage('Please enter a QR code');
            return;
        }
        
        viewerScannedCode.textContent = qrCode;
        viewerScanResult.style.display = 'block';
        findScannedItemInViewer(qrCode);
    }

    function findScannedItemInViewer(qrCode = null) {
        const codeToFind = qrCode || viewerScannedCode.textContent;
        if (!codeToFind) return;

        let found = false;
        let foundCard = null;

        itemCards.forEach(card => {
            const cardQrCode = card.querySelector('.view-details').dataset.qr;
            if (cardQrCode && cardQrCode.toLowerCase() === codeToFind.toLowerCase()) {
                found = true;
                foundCard = card;

                // Highlight the found card
                card.style.backgroundColor = '#d4edda';
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });

                // Show success message
                const itemName = card.querySelector('.card-header h6').textContent;
                showSuccessMessage(`Found item: ${itemName}`);

                // Remove highlight after 3 seconds
                setTimeout(() => {
                    card.style.backgroundColor = '';
                }, 3000);

                return;
            }
        });

        if (!found) {
            showErrorMessage(`No item found with QR code: ${codeToFind}`);
        }
    }

    // Load jsQR library if not already loaded
    if (typeof jsQR === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js';
        script.onload = function() {
            console.log('jsQR library loaded successfully');
        };
        document.head.appendChild(script);
    }
});
