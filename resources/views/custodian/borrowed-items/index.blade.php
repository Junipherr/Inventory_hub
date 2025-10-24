<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/custodian-borrow-requests.css') }}" rel="stylesheet">
    
    <div class="scanner-container">
        <!-- Success Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Error Notification -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Header -->
        <div class="scanner-header">
            <div class="header-content">
                <h1 class="scanner-title">
                    <i class="fas fa-clipboard-list"></i> Borrowed Items Management
                </h1>
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-list"></i>
                        <span>{{ $totalBorrowed ?? 0 }}</span> Total Borrowed
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>{{ $overdueItems ?? 0 }}</span> Overdue
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>{{ $dueThisWeek ?? 0 }}</span> Due This Week
                    </span>
                </div>
            </div>
        </div>

        <div class="scanner-content">
            <!-- Filter Section -->
            <div class="items-section mb-4">
                <div class="items-header">
                    <h3><i class="fas fa-filter"></i> Filter Borrowed Items</h3>
                    <div class="items-controls">
                        <div class="btn-group" role="group">
                            <a href="{{ route('custodian.borrowed-items') }}" 
                               class="btn btn-sm {{ !request('filter') ? 'btn-primary' : 'btn-secondary' }}">
                                All
                            </a>
                            <a href="{{ route('custodian.borrowed-items', ['filter' => 'overdue']) }}" 
                               class="btn btn-sm {{ request('filter') == 'overdue' ? 'btn-primary' : 'btn-secondary' }}">
                                Overdue
                            </a>
                            <a href="{{ route('custodian.borrowed-items', ['filter' => 'due-today']) }}" 
                               class="btn btn-sm {{ request('filter') == 'due-today' ? 'btn-primary' : 'btn-secondary' }}">
                                Due Today
                            </a>
                            <a href="{{ route('custodian.borrowed-items', ['filter' => 'due-week']) }}" 
                               class="btn btn-sm {{ request('filter') == 'due-week' ? 'btn-primary' : 'btn-secondary' }}">
                                Due This Week
                            </a>
                            <a href="{{ route('custodian.borrowed-items', ['filter' => 'returned']) }}" 
                               class="btn btn-sm {{ request('filter') == 'returned' ? 'btn-primary' : 'btn-secondary' }}">
                                Returned
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Borrowed Items Table -->
            <div class="items-table-container">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Borrower</th>
                                <th>Quantity</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowedItems as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->item_name }}</strong>
                                        <small>{{ $item->room_name ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $item->borrower_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->due_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $item->getStatusBadgeClass() }}">
                                            {{ $item->getStatusText() }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('custodian.borrow-requests.show', $item->id) }}" 
                                           class="btn btn-sm btn-info">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
