@extends('virtual::layout')
@section('content')

<div class="max-w-3xl mx-auto mt-6">
  <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-700">
      <h2 class="text-2xl font-semibold text-white">Add New {{ $modelName }}</h2>
    </div>

    <div class="p-6">
      <div class="flex justify-end mb-4">
        <a href="{{ route('virtual.index', ['table'=>$modelNameLower]) }}" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-700 hover:bg-gray-600 text-sm text-gray-300">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
          Back to {{ $modelName }}
        </a>
      </div>

      <form action="{{ route('virtual.store', ['table'=>$modelNameLower]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {!! $formFields !!}

        <div class="flex justify-end">
          <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


<!--need fixes since on button nav to event instead of events-->