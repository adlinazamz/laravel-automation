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

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
        @if ($event)   
         
          <div class="form-group">
    <strong>name:</strong>
    {{ $event->name }}
</div>
<div class="form-group">
    <strong>description:</strong>
    {{ $event->description }}
</div>
<div class="form-group">
    <strong>date_start:</strong>
    {{ $event->date_start }}
</div>
<div class="form-group">
    <strong>date_end:</strong>
    {{ $event->date_end }}
</div>
<div class="form-group">
    <strong>created_at:</strong>
    {{ $event->created_at }}
</div>
<div class="form-group">
    <strong>updated_at:</strong>
    {{ $event->updated_at }}
</div>
    </div>
    @else
        <p>No Events found.</p>
    @endif
  </div>
    </div>
  </div>
</div>

@endsection
