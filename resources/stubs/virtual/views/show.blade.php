@extends('virtual::layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 min-h-screen">
  <div class="mx-auto max-w-4xl px-4 lg:px-6">
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">

      {{-- Header --}}
      <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-700">
        <div>
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
            {{ $modelName }} Details
          </h2>
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Full information for this {{ strtolower($modelName) }}.
          </p>
        </div>
        <a href="{{ route('virtual.index', ['table' => $modelNameLower]) }}"
           class="inline-flex items-center text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 px-3 py-1.5 rounded-lg dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition">
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to {{ Str::plural($modelName) }}
        </a>
      </div>

      {{-- Content --}}
      <div class="p-6 text-gray-700 dark:text-gray-300">
        @php
          // Detect if this model has an image field
          $imagePath = $record['image'] ?? $record['photo'] ?? $record['picture'] ?? null;
        @endphp

        @if($imagePath)
          {{-- Two-column layout when image exists --}}
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Image --}}
            <div class="flex justify-center items-start">
              <img src="{{ Str::startsWith($imagePath, '/storage/') ? $imagePath : '/images/' . $imagePath }}"
                   alt="{{ $record['name'] ?? $modelName }}"
                   class="w-full max-w-sm rounded-xl shadow-md cursor-pointer js-image-modal"
                   data-src="{{ Str::startsWith($imagePath, '/storage/') ? $imagePath : '/images/' . $imagePath }}">
            </div>

            {{-- Fields --}}
            <div class="space-y-4">
              {!! $showFields !!}
            </div>
          </div>
        @else
          {{-- Single-column layout when no image --}}
          <div class="space-y-4">
            {!! $showFields !!}
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
