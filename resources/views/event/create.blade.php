@extends('event.layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
  <div class="mx-auto max-w-screen-md px-4 lg:px-6">
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">

      {{-- Header --}}
      <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
          Add New event
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Fill out the details below to create a new event.
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

        <form action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf

          {{-- Generated form fields --}}
          <div class="space-y-5">
              <div class="mb-4">
    <label for="inputname" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">name:</label>
    <input
        type="text"
        name="name"
        id="inputname"
        placeholder="name"
        
        class="w-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 
               border border-gray-300 dark:border-gray-600 rounded px-3 py-2 
               focus:outline-none focus:ring-2 focus:ring-blue-500 
               @error('name') border-red-500 @enderror"
    >
    @error('name')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
<div class="mb-4">
    <label for="inputdescription" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">description:</label>
    <input
        type="text"
        name="description"
        id="inputdescription"
        placeholder="description"
        
        class="w-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 
               border border-gray-300 dark:border-gray-600 rounded px-3 py-2 
               focus:outline-none focus:ring-2 focus:ring-blue-500 
               @error('description') border-red-500 @enderror"
    >
    @error('description')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
<div class="mb-4">
    <label for="date_start" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">date_start:</label>
    <div class="relative">
        <input
            type="text"
            name="date_start"
            id="date_start"
            placeholder="dd/mm/yyyy"
            
            class="datepicker w-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 
                   border border-gray-300 dark:border-gray-600 rounded px-3 py-2 pr-10 
                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                   @error('date_start') border-red-500 @enderror"
            autocomplete="off"
            data-fp-init="false"
            data-alt-format="d/m/Y"
        >
        <button type="button" class="datepicker-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-300" data-target="#date_start">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 8h12v6H4V8z"/>
            </svg>
        </button>
    </div>
    @error('date_start')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
<div class="mb-4">
    <label for="date_end" class="block text-sm font-medium text-gray-700 dark:text-gray-100 mb-1">date_end:</label>
    <div class="relative">
        <input
            type="text"
            name="date_end"
            id="date_end"
            placeholder="dd/mm/yyyy"
            
            class="datepicker w-full bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-400 
                   border border-gray-300 dark:border-gray-600 rounded px-3 py-2 pr-10 
                   focus:outline-none focus:ring-2 focus:ring-blue-500 
                   @error('date_end') border-red-500 @enderror"
            autocomplete="off"
            data-fp-init="false"
            data-alt-format="d/m/Y"
        >
        <button type="button" class="datepicker-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-300" data-target="#date_end">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 8h12v6H4V8z"/>
            </svg>
        </button>
    </div>
    @error('date_end')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
          </div>

          <div class="flex justify-end">
            <button type="submit"
              class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                     focus:ring-4 focus:ring-blue-300 rounded-lg transition dark:focus:ring-blue-800">
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

@endsection
