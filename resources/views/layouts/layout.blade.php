<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('Inventory', 'Inventory Hub') }}</title>
    <!-- GLOBAL MAINLY STYLES-->
    <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/themify-icons/css/themify-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.min.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('assets/css/rtoolbar-responsive.css') }}" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('assets/css/sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    
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
            </div>
            
        </header>
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
    
    <!-- QRCode Library -->
    
    <script src="{{ asset('js/profile-registration.js') }}" defer></script>
    <script src="{{ asset('assets/js/scanner-fix.js') }}"></script>
    <script src="{{ asset('assets/js/inventory-create.js') }}"></script>

    @stack('scripts')
</body>
</html>
