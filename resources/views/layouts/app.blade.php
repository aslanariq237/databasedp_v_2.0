<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>        
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden relative">
        <!-- Sidebar -->
        <aside id="sidebar"
               class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
            <div class="h-full flex flex-col overflow-y-auto">
                @include('layouts.sidebar')
            </div>
        </aside>

        <!-- Mobile Backdrop -->
        <div id="sidebar-backdrop"
             class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden transition-opacity duration-300"
             style="opacity: 0;"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0 transition-margin duration-300">
            @include('layouts.navbar')            

            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                @yield('content')
            </main>

            <footer class="bg-white border-t border-gray-200 px-6 py-4 text-center text-sm text-gray-600">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </footer>
        </div>
    </div>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>        
    <!-- Vanilla JS untuk Sidebar Mobile & Desktop -->       
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('swal'))
                Swal.fire({
                    title: '{{ session('swal.title') }}',
                    text: '{{ session('swal.text') }}',
                    icon: '{{ session('swal.icon') }}',
                    timer: {{ session('swal.timer') ?? 'null' }},
                    timerProgressBar: {{ session('swal.timerProgressBar') ? 'true' : 'false' }},
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                    allowOutsideClick: false
                });
            @endif
        });
    </script>
    <script>                          
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const toggleButtons = document.querySelectorAll('[data-toggle-sidebar]');

            // Status sidebar: true = terbuka, false = tertutup
            let sidebarOpen = false;

            // Fungsi buka sidebar
            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.style.opacity = '1', 10); // delay kecil untuk transisi
                sidebarOpen = true;
            }

            
            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                backdrop.style.opacity = '0';
                setTimeout(() => backdrop.classList.add('hidden'), 300); // sesuai duration transisi
                sidebarOpen = false;
            }
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    if (sidebarOpen) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            });
            
            backdrop.addEventListener('click', closeSidebar);
            
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) { // lg:
                    closeSidebar(); 
                }
            });
        });        
    </script>            
</body>
</html>