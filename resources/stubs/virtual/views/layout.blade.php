<x-app-layout>
    <x-slot name="header">
        <h2 class="ml-8 text-xl font-semibold text-black dark:text-black">
            {{ __($modelName ?? ucfirst($table)) }}
        </h2>
    </x-slot>

    <x-bootstrap-wrapper>
        @yield('content') 
    </x-bootstrap-wrapper>
</x-app-layout>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
@endpush