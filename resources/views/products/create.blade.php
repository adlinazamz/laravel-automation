@extends('products.layout')
@section('content')

<section class="bg-transparent">
  <div class="max-w-2xl px-4 py-6 mx-auto">
      <h2 class="mb-4 text-xl font-bold text-white">Add New Product</h2>
      <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
              <div class="sm:col-span-2">
                  <label for="name" class="block mb-2 text-sm font-medium text-white">Product Name</label>
                  <input type="text" name="name" id="name" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" placeholder="Type product name" required>
              </div>
              <div class="w-full">
                  <label for="type" class="block mb-2 text-sm font-medium text-white">Type</label>
                  <input type="text" name="type" id="type" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg block w-full p-2.5" placeholder="Product type">
              </div>
              <div class="sm:col-span-2">
                  <label for="detail" class="block mb-2 text-sm font-medium text-white">Description</label>
                  <textarea id="detail" name="detail" rows="6" class="block p-2.5 w-full text-sm text-white bg-gray-700 rounded-lg border border-gray-600" placeholder="Write a product description here..."></textarea>
              </div>
          </div>

          <div class="flex items-center space-x-4">
              <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
                  Submit
              </button>
              <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white border border-gray-600 hover:bg-gray-700 font-medium rounded-lg text-sm px-5 py-2.5">Cancel</a>
          </div>
      </form>
  </div>
</section>
@endsection