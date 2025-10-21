<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full"> {{-- ✅ Add class="h-full" --}}
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Bootstrap Datepicker CSS -->
    @stack('styles')
    </head>

    <body class="h-full font-sans antialiased bg-gray-100"> {{-- ✅ Add class="h-full" here --}}
        <div x-data="{ sidebarOpen: false, collapsed: false }" class="flex min-h-screen h-full bg-gray-100"> {{-- ✅ Add h-full --}}
            
            <!-- Mobile Overlay -->
            <div 
                x-show="sidebarOpen"
                x-cloak
                class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
                @click="sidebarOpen = false"
            ></div>

            <!-- Sidebar Include -->
            @include('layouts.sidenav')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300"
                 :class="collapsed ? 'ml-20' :'ml-45'">
                 
                <!-- Topbar -->
                <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                    <!-- Left: Placeholder for sidebar toggle or branding -->
                    <div class="w-6 h-6"></div>

                    <!-- Right: Auth user dropdown -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 bg-white hover:text-gray-800">
                                        <span>{{ Auth::user()->name }}</span>
                                        <svg class="ml-1 w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.66a.75.75 0 01-1.08 0l-4.25-4.66a.75.75 0 01.02-1.06z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                         onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @endauth
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-6 overflow-y-auto">
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Yielded Page -->
                    {{ $slot }}
                </main>
            </div>
        </div>

        <!-- JS Scripts -->
    <!-- Load jQuery first for any jQuery-dependent plugins (datepicker, daterangepicker, etc) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script><!--flowbite wrapper apexcharts-->
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
        @yield('scripts')
            <script>
                // Initialize bootstrap-datepicker on elements marked by the generator
                document.addEventListener('DOMContentLoaded', function () {
                    if (!window.jQuery) {
                        console.warn('datepicker init: window.jQuery not found');
                        return;
                    }

                    if (typeof window.jQuery.fn.datepicker !== 'function') {
                        console.warn('datepicker init: bootstrap-datepicker plugin not available on jQuery.fn.datepicker');
                        return;
                    }

                    window.jQuery('.datepicker').each(function () {
                        var $el = window.jQuery(this);
                        if (!$el.data('datepicker')) {
                            $el.datepicker({
                                autoclose: true,
                                todayHighlight: true,
                                format: 'dd-mm-yyyy'
                            });
                        }

                        // If the input has a raw value in a different format, try to parse it
                        var raw = $el.val();
                        if (raw) {
                            // try multiple common formats: dmy, ymd, ddmmyy
                            var m = moment(raw, ['DD-MM-YYYY','D-M-YYYY','YYYY-MM-DD','DDMMYY','DMMYY','DD/MM/YYYY','D/M/YYYY'], true);
                            if (!m.isValid()) {
                                // try loose parsing
                                m = moment(raw);
                            }
                            if (m.isValid()) {
                                $el.datepicker('update', m.format('DD-MM-YYYY'));
                            }
                        }
                    });
                });
            </script>
            <!-- Image modal -->
            <div id="image-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">
                <button id="image-modal-close" class="absolute top-4 right-4 text-white text-2xl">&times;</button>
                <div class="max-w-[90vw] max-h-[90vh] p-4">
                    <img id="image-modal-img" src="" alt="" class="w-auto h-auto max-w-full max-h-[90vh] object-contain rounded" />
                </div>
            </div>
    </body>
</html>
