@extends('virtual::layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
  <div class="mx-auto max-w-screen-md px-4 lg:px-6">
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">

      {{-- Header --}}
      <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
          Add New {{ $modelName }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Fill out the details below to create a new {{ strtolower($modelName) }}.
        </p>
      </div>

      {{-- Form --}}
      <div class="p-6">
        <div class="flex justify-end mb-5">
          <a href="{{ route('virtual.index', ['table'=>$modelNameLower]) }}"
             class="inline-flex items-center text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg px-3 py-1.5 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-600">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to {{ Str::plural($modelName) }}
          </a>
        </div>

        <form action="{{ route('virtual.store', ['table'=>$modelNameLower]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf
          {!! $formFields !!}

          <div class="flex justify-end">
            <button type="submit"
              class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 rounded-lg transition dark:focus:ring-blue-800">
              <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<!-- Virtual forms rely on the global flatpickr initializer in layouts/app.blade.php -->
@endpush

@endsection
