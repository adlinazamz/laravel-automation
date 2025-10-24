@extends('event.layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
  <div class="mx-auto max-w-screen-md px-4 lg:px-6">
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">

      {{-- Header --}}
      <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
          Show event
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Displaying details below of event choosen.
        </p>
      </div>

      {{-- Form --}}
      <div class="p-6">
        <div class="flex justify-end mb-5">
          <a href="{{ route('event.index') }}"
             class="inline-flex items-center text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 
                    focus:ring-4 focus:ring-gray-200 rounded-lg px-3 py-1.5 
                    dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-600">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to event
          </a>
        </div>

        {{-- Form --}}
        <form action="{{ route('event.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf
          @method('PUT')

          {{-- Dynamic form fields --}}
          <div class="space-y-5">
            {!! formFields !!}
          </div>

          {{-- Submit --}}
          <div class="flex justify-end">
            <button type="submit"
              class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600
                     focus:ring-4 focus:ring-red-300 rounded-lg transition dark:focus:ring-red-800">
              <i class="fa-solid fa-floppy-disk mr-1"></i> Update
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

@endsection
