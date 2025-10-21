@extends('virtual::layout')
@section('content')

<div class="max-w-3xl mx-auto mt-6">
  <div class="bg-gray-800 rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-700">
      <h2 class="text-xl font-semibold text-white">Show {{ $modelName }}</h2>
    </div>
    <div class="p-6">
      <div class="flex justify-end mb-4">
        <a href="{{ route('virtual.index', ['table'=>$modelNameLower]) }}" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-700 hover:bg-gray-600 text-sm text-gray-300">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
          Back to {{ $modelName }}
        </a>
      </div>

      {!! $showFields !!}
    </div>
  </div>
</div>
@endsection