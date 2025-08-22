<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAC Inventory | Philippine Advent College Inc.</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #7c3aed 100%);
        }
        .no-click {
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header/Navigation -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('assets/img/logos/pac.png') }}" alt="PAC Logo" class="w-10 h-10 mr-3 object-contain">
                <h1 class="text-xl font-bold text-blue-800"><span class="text-blue-600">Inventory</span></h1>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <span class="text-gray-600 no-click">Home</span>
                <span class="text-gray-600 no-click">About</span>
                <span class="text-gray-600 no-click">Inventory</span>
                <span class="text-gray-600 no-click">Contact</span>
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm transition">
                    Login
                </a>
            </div>
            <button class="md:hidden text-gray-600 focus:outline-none" id="mobile-menu-button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <div class="md:hidden" id="mobile-menu" style="display: none;">
            <div class="flex flex-col items-center space-y-4 py-4">
                <span class="text-gray-600 no-click">Home</span>
                <span class="text-gray-600 no-click">About</span>
                <span class="text-gray-600 no-click">Inventory</span>
                <span class="text-gray-600 no-click">Contact</span>
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm transition">
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Streamline Your School Inventory</h1>
                <p class="text-xl mb-8 opacity-90">Philippine Advent College Inc. presents an efficient digital inventory management system for all your academic resources.</p>
                <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                    <span class="bg-white text-blue-700 px-6 py-3 rounded-lg font-medium shadow-md no-click inline-block">
                        Explore Inventory
                    </span>
                    <span class="border-2 border-white text-white px-6 py-3 rounded-lg font-medium no-click inline-block">
                        Learn More
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Inventory Management Features</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Comprehensive Tracking</h3>
                    <p class="text-gray-600">Monitor all school assets from textbooks to lab equipment with detailed records and status updates.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Secure Access</h3>
                    <p class="text-gray-600">Role-based authorization ensures only authorized personnel can access and modify inventory data.</p>
                </div>
                <div class="bg-gray-50 p-8 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Real-time Updates</h3>
                    <p class="text-gray-600">Get instant notifications for inventory changes, low stock alerts, and equipment check-ins/outs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Login CTA Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Ready to Manage Your Inventory?</h2>
            <p class="text-gray-600 max-w-2xl mx-auto mb-8">
                Philippine Advent College Inc.'s inventory system provides faculty and staff with powerful tools to efficiently track and manage all school resources.
            </p>
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-medium shadow-lg text-lg transition">
                Access Inventory Portal
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-8 md:mb-0">
                    <h3 class="text-xl font-bold mb-4">PAC Inventory</h3>
                    <p class="text-gray-400 max-w-xs mb-2">An official inventory management system of Philippine Advent College Inc.</p>
                    <p class="text-gray-400 text-sm italic mb-2">"The school that prepares students to serve."</p>
                    <p class="text-gray-400 text-sm mb-1">Ramon Magsaysay, Sindangan, Zamboanga del Norte</p>
                    <p class="text-gray-400 text-sm mb-1">0917 152 7644</p>
                    <p class="text-gray-400 text-sm mb-1">philippineadventcollege@gmail.com</p>
                    <p class="text-gray-400 text-sm">philippineadventcollege.edu.ph</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-8">
                    <div>
                        <h4 class="font-semibold mb-3">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><span class="text-gray-400 no-click">Home</span></li>
                            <li><span class="text-gray-400 no-click">About</span></li>
                            <li><span class="text-gray-400 no-click">Inventory</span></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-3">Support</h4>
                        <ul class="space-y-2">
                            <li><span class="text-gray-400 no-click">Help Center</span></li>
                            <li><span class="text-gray-400 no-click">Contact Us</span></li>
                            <li><span class="text-gray-400 no-click">FAQ</span></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-3">Legal</h4>
                        <ul class="space-y-2">
                            <li><span class="text-gray-400 no-click">Privacy Policy</span></li>
                            <li><span class="text-gray-400 no-click">Terms of Service</span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-12 pt-8 text-center text-gray-400">
                <p>© 2023 Philippine Advent College Inc. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle functionality
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu.style.display === "none" || mobileMenu.style.display === "") {
                mobileMenu.style.display = "block";
            } else {
                mobileMenu.style.display = "none";
            }
        });
    </script>
</body>
</html>