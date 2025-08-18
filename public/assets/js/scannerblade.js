
        document.addEventListener('DOMContentLoaded', function() {
            // Scanner functionality
            let scanner = null;
            let videoStream = null;

            const startScannerBtn = document.getElementById('startScanner');
            const stopScannerBtn = document.getElementById('stopScanner');
            const video = document.getElementById('scannerVideo');
            const canvas = document.getElementById('scannerCanvas');
            const placeholder = document.getElementById('scannerPlaceholder');
            const scanResult = document.getElementById('scanResult');
            const scannedCode = document.getElementById('scannedCode');
            const findItemBtn = document.getElementById('findItem');
            const manualQrInput = document.getElementById('manualQrInput');
            const manualScanBtn = document.getElementById('manualScanBtn');

            // Search functionality
            const searchInput = document.getElementById('searchItems');
            const itemsTable = document.getElementById('itemsTable');

            // Start camera scanner
            startScannerBtn.addEventListener('click', async () => {
                try {
                    videoStream = await navigator.mediaDevices.getUserMedia({ 
                        video: { facingMode: 'environment' } 
                    });
                    video.srcObject = videoStream;
                    video.style.display = 'block';
                    placeholder.style.display = 'none';
                    startScannerBtn.style.display = 'none';
                    stopScannerBtn.style.display = 'inline-block';
                    
                    // Initialize QR scanner
                    startQrScanning();
                } catch (error) {
                    console.error('Error accessing camera:', error);
                    alert('Unable to access camera. Please ensure camera permissions are granted.');
                }
            });

            // Stop camera scanner
            stopScannerBtn.addEventListener('click', () => {
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null;
                }
                video.style.display = 'none';
                placeholder.style.display = 'flex';
                startScannerBtn.style.display = 'inline-block';
                stopScannerBtn.style.display = 'none';
            });

            // Manual QR input
            manualScanBtn.addEventListener('click', () => {
                const qrCode = manualQrInput.value.trim();
                if (qrCode) {
                    handleManualQrSearch(qrCode);
                }
            });

            manualQrInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const qrCode = manualQrInput.value.trim();
                    if (qrCode) {
                        handleManualQrSearch(qrCode);
                    }
                }
            });

            // Search functionality
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const rows = itemsTable.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // QR code handling
            function handleQrCode(qrCode) {
                scannedCode.textContent = qrCode;
                scanResult.style.display = 'block';
                
                // Find and highlight the item
                const rows = itemsTable.querySelectorAll('tbody tr');
                let found = false;
                
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
                    }
                });

                if (!found) {
                    alert('Item not found with this QR code.');
                }
            }

            // Manual QR search using backend API
            function handleManualQrSearch(qrCode) {
                // Show loading state
                manualScanBtn.disabled = true;
                manualScanBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                // Update scan result display
                scannedCode.textContent = qrCode;
                scanResult.style.display = 'block';
                
                // Make API call to search for item by QR code
                fetch('/scanner/manual-qr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ qr_code: qrCode })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Item found - highlight it in the table
                        const rows = itemsTable.querySelectorAll('tbody tr');
                        let found = false;
                        
                        // First, clear any search filters
                        searchInput.value = '';
                        filterTable('');
                        
                        rows.forEach(row => {
                            const rowQrCode = row.dataset.qrCode;
                            if (rowQrCode === qrCode) {
                                row.classList.add('highlighted');
                                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                row.style.display = ''; // Ensure row is visible
                                found = true;
                                
                                // Remove highlight after 5 seconds
                                setTimeout(() => {
                                    row.classList.remove('highlighted');
                                }, 5000);
                                
                                // Show success message
                                showSuccessMessage(`Item found: ${data.item.item_name}`);
                            } else {
                                row.style.display = ''; // Ensure all rows are visible
                            }
                        });
                        
                        if (!found) {
                            // Item exists but might not be in current view - try to find by item ID
                            const itemId = data.item.id;
                            let foundById = false;
                            
                            rows.forEach(row => {
                                const rowItemId = row.dataset.itemId;
                                if (rowItemId == itemId) {
                                    row.classList.add('highlighted');
                                    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    row.style.display = '';
                                    foundById = true;
                                    
                                    setTimeout(() => {
                                        row.classList.remove('highlighted');
                                    }, 5000);
                                    
                                    showSuccessMessage(`Item found: ${data.item.item_name}`);
                                }
                            });
                            
                            if (!foundById) {
                                // Item truly not in current view
                                showErrorMessage('Item found in database but not visible in current view. Try refreshing the page.');
                            }
                        }
                    } else {
                        // Item not found
                        showErrorMessage(data.message || 'Item not found with this QR code.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Error searching for item. Please check your connection and try again.');
                })
                .finally(() => {
                    // Reset button state
                    manualScanBtn.disabled = false;
                    manualScanBtn.innerHTML = '<i class="fas fa-search"></i>';
                });
            }

            // Helper function to filter table based on search term
            function filterTable(searchTerm) {
                const rows = itemsTable.querySelectorAll('tbody tr');
                const term = searchTerm.toLowerCase();
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (term === '' || text.includes(term)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            // Find item button
            findItemBtn.addEventListener('click', () => {
                const qrCode = scannedCode.textContent;
                if (qrCode) {
                    handleQrCode(qrCode);
                }
            });

            // Status legend modal
            document.getElementById('showLegendBtn').addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('statusLegendModal'));
                modal.show();
            });

            // Item details modal
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', (e) => {
                    const row = e.target.closest('tr');
                    const itemName = row.dataset.itemName;
                    const room = row.dataset.room;
                    const category = row.dataset.category;
                    const quantity = row.dataset.itemQuantity;
                    const status = row.querySelector('.status-select').value;
                    const personInCharge = row.dataset.personInCharge;
                    const qrCode = row.dataset.itemQr;
                    const description = row.dataset.itemDescription;

                    document.getElementById('modalItemName').textContent = itemName;
                    document.getElementById('modalRoom').textContent = room;
                    document.getElementById('modalCategory').textContent = category;
                    document.getElementById('modalQuantity').textContent = quantity;
                    document.getElementById('modalStatus').textContent = status;
                    document.getElementById('modalPersonInCharge').textContent = personInCharge;
                    document.getElementById('modalDescription').textContent = description || 'No description available';
                    
                    // Display QR code
                    const qrCodeImage = document.getElementById('qrCodeImage');
                    if (qrCode) {
                        // Generate QR code image using Google Chart API
                        const qrUrl = `https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=${encodeURIComponent(qrCode)}`;
                        qrCodeImage.src = qrUrl;
                        qrCodeImage.style.display = 'block';
                    } else {
                        qrCodeImage.style.display = 'none';
                        document.getElementById('modalQRCode').innerHTML = '<span>No QR code available</span>';
                    }
                });
            });

            // Form submission
            document.getElementById('scannerForm').addEventListener('submit', (e) => {
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

            // Success message
            let successTimeout; // Add this variable at the top of your <script> block

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

            // QR scanning simulation (for demo purposes)
            function startQrScanning() {
                // This is a placeholder for actual QR scanning
                // In a real implementation, you would use a library like jsQR
                console.log('QR scanning started');
            }

            // Update scanned items count
            function updateScannedCount() {
                const checkedItems = document.querySelectorAll('.status-select').length;
                document.getElementById('scannedItems').textContent = checkedItems;
            }

            // Initialize
            updateScannedCount();
        });
   