<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custodian Inventory Hub - Tarp Mockup</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: #f1f1f1;
            min-height: 100vh;
        }

        /* Tarp Container - 2x3 meter tarp (24x36 inch print dimensions) */
        .tarp-container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            background: #f8f9fa;
            min-height: 1800px;
            aspect-ratio: 2/3;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #253544 0%, #34495f 100%);
            padding: 15px 40px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #3498db 0%, #2ecc71 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-icon i {
            font-size: 25px;
            color: white;
        }

        .header-text {
            flex: 1;
        }

        .main-title {
            font-family: 'Poppins', sans-serif;
            font-size: 30px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-family: 'Open Sans', sans-serif;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 300;
        }

        .header-badge {
            background: rgba(52, 152, 219, 0.3);
            padding: 6px 15px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .header-badge i {
            color: #3498db;
            font-size: 14px;
        }

        .header-badge span {
            font-family: 'Poppins', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: white;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 15px 40px;
            display: flex;
            flex-direction: column;
        }

        /* Screenshots Section - FIXED HEIGHT */
        .screenshots-section {
            margin-bottom: 15px;
        }

        .section-label {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .section-label i {
            color: #3498db;
        }

        .screenshots-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            height: 320px;
        }

        .screenshot-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
        }

        .screenshot-header {
            background: linear-gradient(135deg, #34495f 0%, #2c3e50 100%);
            padding: 5px 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .screenshot-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .screenshot-dot.red { background: #e74c3c; }
        .screenshot-dot.yellow { background: #f39c12; }
        .screenshot-dot.green { background: #2ecc71; }

        .screenshot-content {
            flex: 1;
            background: #f5f6fa;
            padding: 8px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Login Page Content */
        .login-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 8px;
            padding: 12px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .login-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .login-header h1 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .login-header p {
            font-size: 10px;
            opacity: 0.9;
        }

        .login-form {
            width: 100%;
            max-width: 200px;
        }

        .form-group {
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 4px;
            font-size: 10px;
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .form-input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .login-button {
            width: 100%;
            padding: 6px 12px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Dashboard Content */
        .dashboard-page {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .dashboard-header h2 {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .dashboard-header p {
            font-size: 9px;
            opacity: 0.9;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
            margin-bottom: 8px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
            border: 1px solid #e9ecef;
        }

        .stat-number {
            font-size: 14px;
            font-weight: 700;
            color: #3498db;
            display: block;
        }

        .stat-label {
            font-size: 8px;
            color: #6c757d;
            margin-top: 2px;
        }

        .dashboard-nav {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .nav-item {
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 8px;
            color: #495057;
        }

        /* Inventory Content */
        .inventory-page {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .inventory-header {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .inventory-header h2 {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .inventory-header p {
            font-size: 9px;
            opacity: 0.9;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 6px 8px;
            border-radius: 4px;
            margin-bottom: 8px;
            border: 1px solid #e9ecef;
        }

        .filter-row {
            display: flex;
            gap: 4px;
            margin-bottom: 4px;
        }

        .filter-input {
            flex: 1;
            padding: 4px 6px;
            border: 1px solid #ced4da;
            border-radius: 3px;
            font-size: 8px;
        }

        .filter-select {
            padding: 4px 6px;
            border: 1px solid #ced4da;
            border-radius: 3px;
            font-size: 8px;
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 6px;
            flex: 1;
        }

        .item-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 6px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .item-name {
            font-size: 9px;
            font-weight: 600;
            color: #2c3e50;
        }

        .item-badge {
            font-size: 7px;
            padding: 2px 4px;
            border-radius: 3px;
            background: #28a745;
            color: white;
        }

        .item-details {
            font-size: 7px;
            color: #6c757d;
            margin-bottom: 2px;
        }

        .item-description {
            font-size: 7px;
            color: #495057;
            line-height: 1.2;
        }

        .screenshot-footer {
            background: white;
            padding: 6px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .screenshot-label {
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #3498db;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Info Section - Side by Side */
        .info-section {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 15px;
        }

        .info-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .info-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid;
        }

        .info-header.overview {
            border-color: #3498db;
        }

        .info-header.features {
            border-color: #2ecc71;
        }

        .info-icon {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-icon.overview {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        }

        .info-icon.features {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        }

        .info-icon i {
            font-size: 15px;
            color: white;
        }

        .info-title {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Overview List */
        .overview-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .overview-list li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 5px 0;
        }

        .list-bullet {
            width: 16px;
            height: 16px;
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .list-bullet i {
            color: white;
            font-size: 8px;
        }

        .list-text {
            font-size: 12px;
            color: #555;
            line-height: 1.4;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .feature-icon {
            width: 24px;
            height: 24px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            flex-shrink: 0;
        }

        .feature-icon.blue { background: rgba(52, 152, 219, 0.15); color: #3498db; }
        .feature-icon.green { background: rgba(46, 204, 113, 0.15); color: #2ecc71; }
        .feature-icon.teal { background: rgba(26, 188, 156, 0.15); color: #1abc9c; }

        .feature-text {
            font-family: 'Poppins', sans-serif;
            font-size: 9px;
            font-weight: 500;
            color: #2c3e50;
            line-height: 1.3;
        }

        /* Footer Section */
        .footer-section {
            background: linear-gradient(135deg, #253544 0%, #2c3e50 100%);
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .footer-left {
            flex: 1;
        }

        .footer-right {
            flex: 0.7;
            text-align: right;
        }

        .footer-title {
            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #3498db;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        .developers-list {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .developers-list li {
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 9px;
            color: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 6px;
        }

        .tech-badge {
            display: flex;
            align-items: center;
            gap: 4px;
            background: rgba(52, 152, 219, 0.2);
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            color: white;
            border: 1px solid rgba(52, 152, 219, 0.3);
        }

        .tech-badge i {
            font-size: 10px;
        }

        .tech-badge.laravel { background: rgba(231, 76, 60, 0.2); border-color: rgba(231, 76, 60, 0.3); }
        .tech-badge.mysql { background: rgba(52, 152, 219, 0.2); border-color: rgba(52, 152, 219, 0.3); }
        .tech-badge.html { background: rgba(227, 76, 54, 0.2); border-color: rgba(227, 76, 54, 0.3); }
        .tech-badge.js { background: rgba(247, 223, 30, 0.2); border-color: rgba(247, 223, 30, 0.3); color: #333; }

        .school-info {
            color: white;
        }

        .school-name {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .school-year {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.7);
        }

        .tagline {
            font-size: 9px;
            color: rgba(46, 204, 113, 0.9);
            font-style: italic;
            margin-top: 3px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .tarp-container {
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .main-title {
                font-size: 22px;
            }

            .info-section {
                grid-template-columns: 1fr;
            }

            .screenshots-grid {
                grid-template-columns: 1fr;
                height: 200px;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-section {
                flex-direction: column;
                text-align: center;
            }

            .footer-right {
                text-align: center;
            }
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }

            .tarp-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="tarp-container">
        <!-- Header Section -->
        <header class="header-section">
            <div class="logo-icon">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <div class="header-text">
                <h1 class="main-title">CUSTODIAN INVENTORY HUB</h1>
                <p class="subtitle">A Centralized Inventory Management System for School Custodians</p>
            </div>
            <div class="header-badge">
                <i class="fas fa-user-hard-hat"></i>
                <span>Target: Custodian</span>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Screenshots Section -->
            <section class="screenshots-section">
                <div class="section-label">
                    <i class="fas fa-desktop"></i>
                    System Screenshots
                </div>
                <div class="screenshots-grid">
                    <!-- Login Page Content -->
                    <div class="screenshot-item">
                        <div class="screenshot-header">
                            <span class="screenshot-dot red"></span>
                            <span class="screenshot-dot yellow"></span>
                            <span class="screenshot-dot green"></span>
                        </div>
                        <div class="screenshot-content">
                            <div class="login-page">
                                <div class="login-header">
                                    <h1><i class="fas fa-warehouse"></i> School Inventory</h1>
                                    <p>Sign in to manage inventory</p>
                                </div>
                                <div class="login-form">
                                    <div class="form-group">
                                        <input type="text" class="form-input" placeholder="Email or Username">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-input" placeholder="Password">
                                    </div>
                                    <button class="login-button">Sign In</button>
                                </div>
                            </div>
                        </div>
                        <div class="screenshot-footer">
                            <span class="screenshot-label">Login Page</span>
                        </div>
                    </div>

                    <!-- Dashboard Content -->
                    <div class="screenshot-item">
                        <div class="screenshot-header">
                            <span class="screenshot-dot red"></span>
                            <span class="screenshot-dot yellow"></span>
                            <span class="screenshot-dot green"></span>
                        </div>
                        <div class="screenshot-content">
                            <div class="dashboard-page">
                                <div class="dashboard-header">
                                    <h2>Dashboard</h2>
                                    <p>Inventory Management Overview</p>
                                </div>
                                <div class="stats-grid">
                                    <div class="stat-card">
                                        <span class="stat-number">150</span>
                                        <span class="stat-label">Items Available</span>
                                    </div>
                                    <div class="stat-card">
                                        <span class="stat-number">25</span>
                                        <span class="stat-label">Categories</span>
                                    </div>
                                    <div class="stat-card">
                                        <span class="stat-number">8</span>
                                        <span class="stat-label">Rooms</span>
                                    </div>
                                    <div class="stat-card">
                                        <span class="stat-number">12</span>
                                        <span class="stat-label">Active Borrows</span>
                                    </div>
                                </div>
                                <div class="dashboard-nav">
                                    <div class="nav-item">Inventory</div>
                                    <div class="nav-item">Borrow Requests</div>
                                    <div class="nav-item">QR Scanner</div>
                                    <div class="nav-item">Reports</div>
                                </div>
                            </div>
                        </div>
                        <div class="screenshot-footer">
                            <span class="screenshot-label">Dashboard</span>
                        </div>
                    </div>

                    <!-- Inventory Content -->
                    <div class="screenshot-item">
                        <div class="screenshot-header">
                            <span class="screenshot-dot red"></span>
                            <span class="screenshot-dot yellow"></span>
                            <span class="screenshot-dot green"></span>
                        </div>
                        <div class="screenshot-content">
                            <div class="inventory-page">
                                <div class="inventory-header">
                                    <h2>Available Items</h2>
                                    <p>Browse and manage items</p>
                                </div>
                                <div class="filter-section">
                                    <div class="filter-row">
                                        <input type="text" class="filter-input" placeholder="Search items...">
                                        <select class="filter-select">
                                            <option>All Categories</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="items-grid">
                                    <div class="item-card">
                                        <div class="item-header">
                                            <span class="item-name">Projector</span>
                                            <span class="item-badge">5 available</span>
                                        </div>
                                        <div class="item-details">Room A101 • Electronics</div>
                                        <div class="item-description">Digital projector for presentations</div>
                                    </div>
                                    <div class="item-card">
                                        <div class="item-header">
                                            <span class="item-name">Chairs</span>
                                            <span class="item-badge">20 available</span>
                                        </div>
                                        <div class="item-details">Room B205 • Furniture</div>
                                        <div class="item-description">Stackable classroom chairs</div>
                                    </div>
                                    <div class="item-card">
                                        <div class="item-header">
                                            <span class="item-name">Whiteboard</span>
                                            <span class="item-badge">3 available</span>
                                        </div>
                                        <div class="item-details">Room C301 • Supplies</div>
                                        <div class="item-description">Magnetic whiteboard with markers</div>
                                    </div>
                                    <div class="item-card">
                                        <div class="item-header">
                                            <span class="item-name">Laptop</span>
                                            <span class="item-badge">8 available</span>
                                        </div>
                                        <div class="item-details">Room D401 • Electronics</div>
                                        <div class="item-description">School laptops for student use</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="screenshot-footer">
                            <span class="screenshot-label">Inventory Management</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Info Section -->
            <section class="info-section">
                <!-- Overview Card -->
                <div class="info-card">
                    <div class="info-header overview">
                        <div class="info-icon overview">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h2 class="info-title">System Overview</h2>
                    </div>
                    <ul class="overview-list">
                        <li>
                            <span class="list-bullet">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="list-text">Centralized tracking and management of school properties</span>
                        </li>
                        <li>
                            <span class="list-bullet">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="list-text">Accurate monitoring of supplies and equipment</span>
                        </li>
                        <li>
                            <span class="list-bullet">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="list-text">Improves accountability and operational efficiency</span>
                        </li>
                    </ul>
                </div>

                <!-- Features Card -->
                <div class="info-card">
                    <div class="info-header features">
                        <div class="info-icon features">
                            <i class="fas fa-star"></i>
                        </div>
                        <h2 class="info-title">Key Features</h2>
                    </div>
                    <div class="features-grid">
                        <div class="feature-item">
                            <div class="feature-icon blue">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <span class="feature-text">Inventory Tracking</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon green">
                                <i class="fas fa-tags"></i>
                            </div>
                            <span class="feature-text">Item Records</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon teal">
                                <i class="fas fa-exchange-alt"></i>
                            </div>
                            <span class="feature-text">Stock In/Out</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon blue">
                                <i class="fas fa-lock"></i>
                            </div>
                            <span class="feature-text">Secure Login</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon green">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <span class="feature-text">Status Overview</span>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon teal">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <span class="feature-text">QR Scanning</span>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer Section -->
        <footer class="footer-section">
            <div class="footer-left">
                <h3 class="footer-title">Developers</h3>
                <ul class="developers-list">
                    <li>Junipher Nathaniel Bayron</li>
                    <li>Alphono De Guzman</li>
                    <li>Angeline Gumo-ay</li>
                    <li>Reynaldo Saavedra</li>
                </ul>
                <div class="tech-stack">
                    <span class="tech-badge laravel"><i class="fab fa-laravel"></i> Laravel</span>
                    <span class="tech-badge mysql"><i class="fas fa-database"></i> MySQL</span>
                    <span class="tech-badge html"><i class="fab fa-html5"></i> HTML</span>
                    <span class="tech-badge js"><i class="fab fa-js"></i> JavaScript</span>
                </div>
            </div>
            <div class="footer-right">
                <div class="school-info">
                    <h3 class="school-name">Philippine Advent College</h3>
                    <p class="school-year">School Year 2025–2026</p>
                    <p class="tagline">"Efficiency & Accountability in Property Management"</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
