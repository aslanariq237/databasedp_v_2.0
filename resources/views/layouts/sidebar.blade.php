<nav class="h-full flex flex-col py-4 px-4 overflow-y-auto">
    <!-- Logo -->
    <div class="p-4 flex items-center space-x-3 border-b border-gray-800">
        <div class=" p-2 rounded-lg flex-shrink-0">
            <div style="border: 2px solid white;">
                <div style="border: 2px solid white; text-align:center; margin: 2px;">
                    <h1 style="font-size: 25px; margin-left: 5px; margin-right: 5px">DP</h1>
                </div>
            </div>
        </div>
        <h2 id="sidebar-title" class="text-xl font-bold">Dataprint</h2>
    </div>

    <ul class="space-y-2">
        <!-- Dashboard -->
        <li>
            <a href="{{ route('dashboard') }}"
               class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-gray-800 {{ request()->routeIs('dashboard') ? 'bg-gray-800' : '' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
        </li>
        <!-- Module: Master Data -->
        <li>
            <button data-submenu="master"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-8 0h8"/>
                    </svg>
                    Master Data
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul id="submenu-master" class="submenu ml-8 mt-2 space-y-1 hidden">                                
                <li><a href="{{ route('customer.index')}}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Customer</a></li>
                <li><a href="{{ route('keluh.index')}}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Keluhan</a></li>
                <li><a href="{{ route('teknisi.index') }}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Teknisi</a></li>
                <li><a href="{{ route('product.index') }}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Product</a></li>
            </ul>
        </li>
        <!-- Module: Receive -->
        <li>
            <button data-submenu="receive"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    Receive
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul id="submenu-receive" class="submenu ml-8 mt-2 space-y-1 hidden">
                <li><a href="{{ route('receives.index') }}" class="block py-2 text-sm hover:text-indigo-300">Penerimaan Barang</a></li>
                <li><a href="{{ route('keluhan.index') }}" class="block py-2 text-sm hover:text-indigo-300">Keluhan</a></li>
            </ul>
        </li>        
        {{-- Module: Document --}}
        <li>
            <button data-submenu="document"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-8 0h8"/>
                    </svg>
                    Document
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul id="submenu-document" class="submenu ml-8 mt-2 space-y-1 hidden">
                <li><a href="{{ route('admin.index') }}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Admin</a></li>                
                <li><a href="{{ route('invoices.index') }}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Invoice</a></li>                
                <li><a href="{{ route('surat-jalan.index') }}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Surat Jalan</a></li>                                            
            </ul>
        </li>

        {{-- Module: finance --}}
        <li>
            <button data-submenu="finance"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-8 0h8"/>
                    </svg>
                    Finance
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul id="submenu-finance" class="submenu ml-8 mt-2 space-y-1 hidden">
                <li><a href="{{ route('receivables.index') }}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Pembayaran</a></li>                                    
            </ul>
        </li>
        {{-- Module: Report --}}
        <li>
            <button data-submenu="report"
                    class="w-full flex items-center justify-between px-4 py-3 text-white rounded-lg hover:bg-gray-800">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-8 0h8"/>
                    </svg>
                    Report
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <ul id="submenu-report" class="submenu ml-8 mt-2 space-y-1 hidden">
                <li><a href="{{ route('report.teknisi') }}" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Teknisi</a></li>                                
                <li><a href="#" class="block py-2 text-sm font-semibold text-gray-400 hover:text-indigo-400">Transaksi</a></li>                                
            </ul>
        </li>

        <!-- Finance & Report (sama seperti Master Data) -->
        <!-- ... -->
    </ul>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggleButtons = document.querySelectorAll('[data-toggle-sidebar]');

        let sidebarOpen = false;

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            setTimeout(() => backdrop.style.opacity = '1', 10);
            sidebarOpen = true;
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            backdrop.style.opacity = '0';
            setTimeout(() => backdrop.classList.add('hidden'), 300);
            sidebarOpen = false;
        }

        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                sidebarOpen ? closeSidebar() : openSidebar();
            });
        });

        backdrop.addEventListener('click', closeSidebar);

        // === COLLAPSIBLE SUBMENU ===
        const submenuButtons = document.querySelectorAll('[data-submenu]');
        submenuButtons.forEach(button => {
            button.addEventListener('click', function () {
                const submenuId = 'submenu-' + this.dataset.submenu;
                const submenu = document.getElementById(submenuId);
                const icon = this.querySelector('svg');

                if (submenu) {
                    submenu.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                }
            });
        });
    });
</script>