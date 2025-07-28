//QR
  document.addEventListener('DOMContentLoaded', function() {
            var modals = document.querySelectorAll('.modal');
            modals.forEach(function(modal) {
                modal.addEventListener('show.bs.modal', function(event) {
                    var qrData = modal.getAttribute('data-qr');
                    var qrcodeContainer = modal.querySelector('[id^="qrcode-"]');
                    if (qrcodeContainer) {
                        qrcodeContainer.innerHTML = '';
                        if (qrData) {
                            QRCode.toCanvas(qrcodeContainer, qrData, {
                                width: 150,
                                margin: 2
                            }, function(error) {
                                if (error) console.error('QRCode error:', error);
                            });
                        }
                    }
                });
            });
        });