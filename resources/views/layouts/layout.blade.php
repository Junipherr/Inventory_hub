<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('Inventory', 'Inventory Hub') }}</title>
    
    <!-- Preloader CSS - loaded first to ensure immediate display -->
    <style>
        /* Immediately show preloader and hide page content until loaded */
        body.preloader-loading .preloader-backdrop {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
        }
        
        /* Hide main content while preloader is active */
        body.preloader-loading .page-wrapper,
        body.preloader-loading .content-wrapper,
        body.preloader-loading .header,
        body.preloader-loading .page-sidebar {
            visibility: hidden !important;
        }
        
        /* Preloader backdrop - hidden by default */
        .preloader-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            z-index: 9999;
        }
        
        /* Preloader animation */
        .preloader-backdrop .page-preloader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 16px;
            color: #333;
        }
        
        .preloader-backdrop .page-preloader::before {
            content: '';
            display: block;
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
            border: 3px solid #eee;
            border-top-color: #18c5a9;
            border-radius: 50%;
            animation: preloader-spin 1s linear infinite;
        }
        
        @keyframes preloader-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    <!-- Immediately activate preloader before any other content -->
    <script>
        // Show preloader immediately to prevent flash of content
        document.documentElement.classList.add('preloader-loading');
    </script>
    
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('assets/css/rtoolbar-responsive.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Sidebar and mobile menu CSS now loaded in their respective partials -->
    
    @vite(['resources/css/app.css','resources/js/app.js', 'resources/js/sidebar_fix.js'])
</head>

@php
    $isScannerPage = request()->routeIs('scanner') || request()->routeIs('scanner.*');
@endphp
<body class="fixed-navbar sidebar-mini has-animation {{ $isScannerPage ? 'scanner-page' : '' }}">
    @if (session('success'))
        <div class="alert alert-success position-fixed" id="successNotification"
            style="top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px;">
            <strong>Success!</strong> {{ session('success') }}
        </div>
    @endif
    <div class="page-wrapper ">
        <header class="header">
            @include('partials.startheader')
            <div class="flexbox flex-1">
                @include('partials.toolbar')
                @include('partials.rtoolbar')
                
                <!-- Mobile Menu Toggle Button -->
                <button class="navbar-toggler d-md-none mobile-menu-toggle" type="button" 
                        aria-label="Toggle navigation">
                    <i class="ti-menu"></i>
                </button>
            </div>
        </header>
        
        <!-- Mobile Navigation Menu -->
        <div class="d-md-none mobile-nav-container" id="mobileNavMenu">
            @include('partials.mobilemenu')
        </div>
        
        @include('partials.sidebar')
        <div class="content-wrapper">
            {{ $slot }}
        </div>
    </div>

    <div class="sidenav-backdrop backdrop"></div>
    {{-- Tuyok2 --}}
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/vendors/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/popper.js/dist/umd/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/metisMenu/dist/metisMenu.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/chart.js/dist/Chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-us-aea-en.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/category.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/sidebar_fix.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/app.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/scripts/dashboard_1_demo.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/dropdown-fix.js') }}" type="text/javascript"></script>
    <!-- Mobile menu JS now loaded in its partial -->
    
    <!-- QRCode Library -->
    
    
    <script src="{{ asset('js/profile-registration.js') }}" defer></script>
    <script src="{{ asset('assets/js/scanner-fix.js') }}"></script>
    <script src="{{ asset('assets/js/inventory-create.js') }}"></script>

    @stack('scripts')

    <script>
        // Preloader hide logic - run when page is fully loaded
        window.addEventListener('load', function() {
            setTimeout(function() {
                // Fade out preloader
                var preloader = document.querySelector('.preloader-backdrop');
                if (preloader) {
                    preloader.style.opacity = '0';
                    preloader.style.transition = 'opacity 0.2s ease-out';
                    setTimeout(function() {
                        preloader.style.display = 'none';
                    }, 200);
                }
                // Remove preloader-loading class to show page content
                document.documentElement.classList.remove('preloader-loading');
                document.body.classList.remove('preloader-loading');
            }, 100); // Small delay to ensure smooth transition
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            var mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
            var mobileNavMenu = document.getElementById('mobileNavMenu');
            var header = document.querySelector('.header');
            var isOpen = false;
            if (mobileMenuToggle && mobileNavMenu) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    isOpen = !isOpen;
                    if (isOpen) {
                        mobileNavMenu.style.display = 'block';
                        mobileNavMenu.classList.add('mobile-menu-open');
                        if (header) header.classList.add('mobile-menu-blur');
                        mobileMenuToggle.style.zIndex = '1101';
                    } else {
                        mobileNavMenu.style.display = 'none';
                        mobileNavMenu.classList.remove('mobile-menu-open');
                        if (header) header.classList.remove('mobile-menu-blur');
                        mobileMenuToggle.style.zIndex = '';
                    }
                });
                // Optional: Hide menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (isOpen && !mobileNavMenu.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                        mobileNavMenu.style.display = 'none';
                        mobileNavMenu.classList.remove('mobile-menu-open');
                        if (header) header.classList.remove('mobile-menu-blur');
                        mobileMenuToggle.style.zIndex = '';
                        isOpen = false;
                    }
                });
            }
        });
    </script>
</body>
</html>
