<x-app-layout>
    
    <div class="min-h-screen bg-gray-900 text-gray-200">
        <main class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gray-800 rounded-xl shadow-md overflow-hidden">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
</x-app-layout>