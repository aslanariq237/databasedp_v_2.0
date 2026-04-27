<nav
    x-data="{ open: false}" 
    class="bg-white shadow-sm border-b border-gray-200"    
>
    <div class="px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <!-- Tombol Toggle Sidebar -->
            <button 
                data-toggle-sidebar class="p-2 rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none"
                id="user-menu-button"       
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">                
                {{ __('Dashboard') }}
            </x-nav-link>            
            {{-- <x-nav-link :href="route('receives.index')" :active="request()->routeIs('receives')">
                {{ __('Receive') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.index')" :active="request()->routeIs('admin')">
                {{ __('Admin') }}
            </x-nav-link>
            <x-nav-link :href="route('receivables.index')" :active="request()->routeIs('finance')">
                {{ __('Finance') }}
            </x-nav-link>             --}}
        </div>

        <!-- User Section tetap sama -->
        <div class="flex items-center space-x-4">
            <!-- Notification & User Dropdown (tanpa Alpine) -->
            <button class="p-2 rounded-full hover:bg-gray-100 relative">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </button>
            <div class="" id="user-menu" hidden></div>
            <div class="relative">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- <x-dropdown-link :href="route('profile.edit')"> --}}
                        <x-dropdown-link>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>

<script>
    // Dropdown user menu (vanilla JS)
    document.getElementById('user-menu-button').addEventListener('click', function () {
        document.getElementById('user-menu').classList.toggle('hidden');
    });

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        const menu = document.getElementById('user-menu');
        const button = document.getElementById('user-menu-button');
        if (!button.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>