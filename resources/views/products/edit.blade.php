@extends('products.layout')
@section('content')

<div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded-lg">
  <h2 class="text-xl font-semibold bg-gray-100 px-6 py-4 rounded-t-lg">Edit Product</h2>
  <div class="px-6 py-4">

    <div class="flex justify-end mb-4">
      <a href="{{ route('products.index') }}" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-2 rounded inline-flex items-center">
        <i class="fa fa-arrow-left mr-1"></i> Back to Products
      </a>
    </div>

    <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      {{-- Name --}}
      <div class="mb-4">
        <label for="inputName" class="block text-sm font-medium text-gray-700 mb-1">Name:</label>
        <input
          type="text"
          name="name"
          id="inputName"
          placeholder="Name"
          value="{{$product -> name}}"
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 @error('name') border-red-500 @enderror"
        >
        @error('name')
          <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Details --}}
      <div class="mb-4">
        <label for="inputDetail" class="block text-sm font-medium text-gray-700 mb-1">Details:</label>
        <textarea
          name="detail"
          id="inputDetail"
          placeholder="Detail"
          rows="5"
          value="{{$product -> detail}}"
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 @error('detail') border-red-500 @enderror"
        ></textarea>
        @error('detail')
          <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Type --}}
      <div class="mb-4">
        <label for="inputType" class="block text-sm font-medium text-gray-700 mb-1">Type:</label>
        <input
          type="text"
          name="type"
          id="inputType"
          placeholder="Type"
          value="{{$product -> type}}"
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 @error('type') border-red-500 @enderror"
        >
        @error('type')
          <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Image --}}
      <div class="mb-4">
        <label for="inputImage" class="block text-sm font-medium text-gray-700 mb-1">Image:</label>
        <input
          type="file"
          name="image"
          id="inputImage"
          class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 @error('image') border-red-500 @enderror"
        >
        <img src = "{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}" width  ="300px">
        @error('image')
          <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
      </div>

      {{-- Submit --}}
      <div class="flex justify-end">
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded inline-flex items-center">
          <i class="fa-solid fa-floppy-disk mr-1"></i> Update
        </button>
      </div>

    </form>
  </div>
</div>
@endsection