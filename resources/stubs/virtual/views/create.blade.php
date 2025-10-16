@extends('virtual::layout')
@section('content')

<div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded-lg">
  <h2 class="text-xl font-semibold bg-gray-100 px-6 py-4 rounded-t-lg">Add New {{$modelName}}</h2>
  <div class="px-6 py-4">

    <div class="flex justify-end mb-4">
      <a href="{{ route('virtual.index', ['table'=>$modelNameLower]) }}" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-2 rounded inline-flex items-center">
        <i class="fa fa-arrow-left mr-1"></i> Back to {{$modelName}}
      </a>
    </div>

    <form action="{{ route('virtual.store', ['table'=>$modelNameLower]) }}" method="POST" enctype="multipart/form-data">
      @csrf

      {!! $formFields!!}

      {{-- Submit --}}
      <div class="flex justify-end">
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded inline-flex items-center">
          <i class="fa-solid fa-floppy-disk mr-1"></i> Submit
        </button>
      </div>
    </form>
  </div>
</div>
@endsection