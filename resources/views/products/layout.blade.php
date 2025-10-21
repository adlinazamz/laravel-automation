<x-app-layout>
    <x-slot name="header">
        <h2 class="ml-8 text-xl font-semibold text-white">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </div>
</x-app-layout>

@push('styles')
    {{-- Keep Font Awesome only; remove Bootstrap/DataTables CSS to prevent conflicts with Tailwind dark theme --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
@endpush