/**
 * Admin Borrow Requests Enhanced JavaScript
 * Provides advanced functionality for the admin interface
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize admin functionality
    initAdminBorrowRequests();
});

function initAdminBorrowRequests() {
    // Initialize bulk operations
    initBulkOperations();
    
    // Initialize real-time notifications
    initNotifications();
    
    // Initialize advanced filtering
    initAdvancedFilters();
    
    // Initialize action buttons
    initActionButtons();
}

// Bulk Operations
function initBulkOperations() {
    const bulkSelectAll = document.getElementById('bulk-select-all');
    const bulkCheckboxes = document.querySelectorAll('.bulk-checkbox');
    const bulkActions = document.querySelectorAll('.bulk-action');
    
    if (bulkSelectAll) {
        bulkSelectAll.addEventListener('change', function() {
            bulkCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }
    
    bulkCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('.bulk-checkbox:checked').length;
        bulkActions.forEach(action => {
            action.disabled = checkedCount === 0;
        });
    }
}

// Real-time Notifications
function initNotifications() {
    // Simulate real-time updates (in production, use WebSocket or SSE)
    setInterval(() => {
        checkForUpdates();
    }, 30000); // Check every 30 seconds
    
    function checkForUpdates() {
        fetch('/admin/borrow-requests/check-updates', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.hasUpdates) {
                showNotification('New borrow requests available', 'info');
                updateStats(data.stats);
            }
        });
    }
}

// Advanced Filtering
function initAdvancedFilters() {
    const filterForm = document.getElementById('filter-form');
    const dateRangePicker = document.getElementById('date-range');
    const statusFilter = document.getElementById('status-filter');
    
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }
    
    if (dateRangePicker) {
        // Initialize date range picker (using flatpickr or similar)
        flatpickr(dateRangePicker, {
            mode: "range",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates) {
                applyFilters();
            }
        });
    }
    
    function applyFilters() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        
        fetch('/admin/borrow-requests/filter?' + params.toString(), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            updateTable(data.html);
            updateStats(data.stats);
        });
    }
}

// Action Buttons
function initActionButtons() {
    // Approve request
    document.querySelectorAll('.approve-request').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.dataset.id;
            approveRequest(requestId);
        });
    });
    
    // Reject request
    document.querySelectorAll('.reject-request').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.dataset.id;
            rejectRequest(requestId);
        });
    });
    
    // Mark as returned
    document.querySelectorAll('.mark-returned').forEach(button => {
        button.addEventListener('click', function() {
            const requestId = this.dataset.id;
            markAsReturned(requestId);
        });
    });
}

// API Functions
function approveRequest(requestId) {
    if (confirm('Are you sure you want to approve this borrow request?')) {
        fetch(`/admin/borrow-requests/${requestId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Request approved successfully', 'success');
                updateRow(requestId, data.html);
                updateStats(data.stats);
            } else {
                showNotification(data.message || 'Error approving request', 'error');
            }
        });
    }
}

function rejectRequest(requestId) {
    const reason = prompt('Please provide a reason for rejection:');
    if (reason && reason.trim() !== '') {
        fetch(`/admin/borrow-requests/${requestId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Request rejected successfully', 'success');
                updateRow(requestId, data.html);
                updateStats(data.stats);
            } else {
                showNotification(data.message || 'Error rejecting request', 'error');
            }
        });
    }
}

function markAsReturned(requestId) {
    if (confirm('Are you sure you want to mark this item as returned?')) {
        fetch(`/admin/borrow-requests/${requestId}/mark-returned`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Item marked as returned', 'success');
                updateRow(requestId, data.html);
                updateStats(data.stats);
            } else {
                showNotification(data.message || 'Error marking as returned', 'error');
            }
        });
    }
}

// Utility Functions
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show`;
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.insertBefore(notification, document.body.firstChild);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

function updateRow(requestId, html) {
    const row = document.querySelector(`tr[data-id="${requestId}"]`);
    if (row) {
        row.outerHTML = html;
    }
}

function updateTable(html) {
    const tbody = document.querySelector('.admin-table tbody');
    if (tbody) {
        tbody.innerHTML = html;
    }
}

function updateStats(stats) {
    // Update stat cards
    document.querySelector('.stat-card.primary .stat-number').textContent = stats.total;
    document.querySelector('.stat-card.success .stat-number').textContent = stats.approved;
    document.querySelector('.stat-card.warning .stat-number').textContent = stats.pending;
    document.querySelector('.stat-card.danger .stat-number').textContent = stats.rejected;
}

// Export Functions
function exportToPDF() {
    const selected = Array.from(document.querySelectorAll('.bulk-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        showNotification('Please select items to export', 'warning');
        return;
    }
    
    window.open(`/admin/borrow-requests/export/pdf?ids=${selected.join(',')}`, '_blank');
}

function exportToExcel() {
    const selected = Array.from(document.querySelectorAll('.bulk-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        showNotification('Please select items to export', 'warning');
        return;
    }
    
    window.open(`/admin/borrow-requests/export/excel?ids=${selected.join(',')}`, '_blank');
}

// Initialize DataTables
function initDataTables() {
    const table = document.getElementById('borrowRequestsTable');
    if (table) {
        $(table).DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'desc']],
            columnDefs: [
                { orderable: false, targets: [-1] }
            ],
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initDataTables();
});
