<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        @php
                            $siteSettings = \App\Models\HomepageBanner::getActive();
                        @endphp
                        @if($siteSettings && $siteSettings->site_logo)
                            <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="{{ config('app.name') }}" class="block h-9 w-auto">
                        @else
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                Admin Dashboard
                            </x-nav-link>
                            <x-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                                Payments
                            </x-nav-link>
                        @endif
                        
                        @if(auth()->user()->isDeveloper())
                            <x-nav-link :href="route('developer.dashboard')" :active="request()->routeIs('developer.dashboard')">
                                Dashboard
                            </x-nav-link>
                            <x-nav-link :href="route('developer.properties.index')" :active="request()->routeIs('developer.properties.*')">
                                Properties
                            </x-nav-link>
                            <x-nav-link :href="route('developer.analytics')" :active="request()->routeIs('developer.analytics')">
                                Analytics
                            </x-nav-link>
                            <x-nav-link :href="route('developer.invoices')" :active="request()->routeIs('developer.invoices*')">
                                Invoices
                            </x-nav-link>
                        @endif
                        
                        @if(auth()->user()->isServiceProvider())
                            <x-nav-link :href="route('service-provider.dashboard')" :active="request()->routeIs('service-provider.dashboard')">
                                Dashboard
                            </x-nav-link>
                            <x-nav-link :href="route('service-provider.services.index')" :active="request()->routeIs('service-provider.services.*')">
                                Services
                            </x-nav-link>
                            <x-nav-link :href="route('service-provider.analytics')" :active="request()->routeIs('service-provider.analytics')">
                                Analytics
                            </x-nav-link>
                            <x-nav-link :href="route('service-provider.invoices')" :active="request()->routeIs('service-provider.invoices*')">
                                Invoices
                            </x-nav-link>
                        @endif
                    @else
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            Home
                        </x-nav-link>
                        <x-nav-link :href="route('properties')" :active="request()->routeIs('properties')">
                            Properties
                        </x-nav-link>
                        <x-nav-link :href="route('services')" :active="request()->routeIs('services')">
                            Services
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                Profile
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    Log Out
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700">Login</a>
                        <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                @if(auth()->user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Admin Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.payments')" :active="request()->routeIs('admin.payments')">
                        Payments
                    </x-responsive-nav-link>
                @endif
                
                @if(auth()->user()->isDeveloper())
                    <x-responsive-nav-link :href="route('developer.dashboard')" :active="request()->routeIs('developer.dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('developer.properties.index')" :active="request()->routeIs('developer.properties.*')">
                        Properties
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('developer.analytics')" :active="request()->routeIs('developer.analytics')">
                        Analytics
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('developer.invoices')" :active="request()->routeIs('developer.invoices*')">
                        Invoices
                    </x-responsive-nav-link>
                @endif
                
                @if(auth()->user()->isServiceProvider())
                    <x-responsive-nav-link :href="route('service-provider.dashboard')" :active="request()->routeIs('service-provider.dashboard')">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('service-provider.services.index')" :active="request()->routeIs('service-provider.services.*')">
                        Services
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('service-provider.analytics')" :active="request()->routeIs('service-provider.analytics')">
                        Analytics
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('service-provider.invoices')" :active="request()->routeIs('service-provider.invoices*')">
                        Invoices
                    </x-responsive-nav-link>
                @endif
            @else
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    Home
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('properties')" :active="request()->routeIs('properties')">
                    Properties
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('services')" :active="request()->routeIs('services')">
                    Services
                </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        Profile
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            Log Out
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>