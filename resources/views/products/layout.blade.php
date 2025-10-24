<x-app-layout>
    <div class="w-full h-full mx-auto px-4 lg:px-8">
            @yield('content')
    </div>
</x-app-layout>

@push('styles')
    {{-- Keep Font Awesome only; remove Bootstrap/DataTables CSS to prevent conflicts with Tailwind dark theme --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
@endpush