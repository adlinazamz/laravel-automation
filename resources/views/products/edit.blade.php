@extends('products.layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
  <div class="mx-auto max-w-screen-md px-4 lg:px-6">
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">

      {{-- Header --}}
      <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
          Edit Product
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Edit the details below to update the product.
        </p>
      </div>

      {{-- Form --}}
      <div class="p-6">
        <div class="flex justify-end mb-5">
          <a href="{{ route('products.index') }}"
             class="inline-flex items-center text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg px-3 py-1.5 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-600">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to products
          </a>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">

            {{-- Product Name --}}
            <div class="sm:col-span-2">
              <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
              <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                     class="bg-white dark:bg-gray-700 border border-gray-600 text-gray-700 dark:text-white text-sm rounded-lg block w-full p-2.5"
                     placeholder="Type product name" required>
            </div>

            {{-- Product Type --}}
            <div class="w-full relative">
              <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Type</label>
              <button id="typeDropdownButton"
                      type="button"
                      class="flex justify-between items-center w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                             text-gray-700 dark:text-white text-sm rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                <span id="selectedType">{{ old('type', $product->type ?? 'Select or add new product type') }}</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
              </button>
              {{-- Hidden actual input --}}
  <input type="hidden" name="type" id="typeInput" value="{{ old('type', $product->type) }}">

              <div id="typeDropdownMenu"
                   class="hidden absolute z-10 mt-2 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg shadow-lg transition-all">
                   <ul id="typeList" class="max-h-48 overflow-y-auto">
                      @foreach($types as $type)
                          <li>
                              <button type="button"
                                      class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 select-type"
                                      data-type="{{ $type }}">
                                      {{ $type }}
                              </button>
                          </li>
                  @endforeach
                </ul>
                <div class="border-t border-gray-700 my-1"></div>
                <div class="p-3">
                      <input type="text" id="newTypeInput"
                             class="w-full mb-2 px-3 py-2 text-sm bg-gray-50 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                             placeholder="Add new type">
                      <button type="button" id="addTypeBtn"
                              class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg px-3 py-2 transition">
                              Add New Type
                      </button>
                  </div>
              </div>
            </div>

            {{-- Description --}}
            <div class="sm:col-span-2">
              <label for="detail" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
              <textarea id="detail" name="detail" rows="6"
                        class="block p-2.5 w-full text-sm bg-white dark:bg-gray-700 text-gray-700 dark:text-white rounded-lg border border-gray-600"
                        placeholder="Write a product description here...">{{ old('detail', $product->detail) }}</textarea>
            </div>

            {{-- Image Upload --}}
            <div class="sm:col-span-2">
              <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Image</label>

              <div class="flex items-center bg-white dark:bg-gray-700 border border-gray-600 rounded-lg overflow-hidden">
                <label for="image"
                       class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 cursor-pointer whitespace-nowrap">
                  Choose File
                </label>

                <input id="image" name="image" type="file" accept="image/*"
                       class="hidden"
                       onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'No file chosen'">

                <span id="file-name"
                      class="flex-1 text-sm text-gray-500 dark:text-gray-300 px-3 py-2 truncate select-none">
                  No file chosen
                </span>
              </div>

              @if ($product->image)
                <div class="mt-3 flex items-center space-x-3">
                  <img src="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}"
                       alt="Current Image"
                       class="w-20 h-20 object-cover rounded-md border border-gray-600">
                  <span class="text-sm text-gray-700 dark:text-gray-300">{{ basename($product->image) }}</span>
                </div>
              @endif
              <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Accepted: JPG, PNG, GIF (Max: 2MB)</p>
            </div>
          </div>

          {{-- Buttons --}}
          <div class="flex items-center space-x-4 mt-5">
            <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5">
              Update
            </button>
            <a href="{{ route('products.index') }}"
               class="text-gray-700 dark:text-gray-200 hover:text-white border border-gray-600 hover:bg-gray-700 font-medium rounded-lg text-sm px-5 py-2.5">
              Cancel
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

{{-- Script --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const dropdownButton = document.getElementById('typeDropdownButton');
    const dropdownMenu = document.getElementById('typeDropdownMenu');
    const selectedType = document.getElementById('selectedType');
    const typeInput = document.getElementById('typeInput');
    const addTypeBtn = document.getElementById('addTypeBtn');
    const newTypeInput = document.getElementById('newTypeInput');
    const typeList = document.getElementById('typeList');

    dropdownButton.addEventListener('click', (e) => {
      e.stopPropagation();
      dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (e) => {
      if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.add('hidden');
      }
    });

    // Select existing type
    document.querySelectorAll('.select-type').forEach(btn => {
      btn.addEventListener('click', () => {
        const type = btn.dataset.type;
        selectedType.textContent = type;
        typeInput.value = type;
        dropdownMenu.classList.add('hidden');
      });
    });

    // Add new type
    addTypeBtn.addEventListener('click', () => {
      const newType = newTypeInput.value.trim();
      if (newType) {
        const newBtn = document.createElement('button');
        newBtn.type = 'button';
        newBtn.className = 'w-full text-left px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 select-type';
        newBtn.dataset.type = newType;
        newBtn.textContent = newType;
        newBtn.addEventListener('click', () => {
          selectedType.textContent = newType;
          typeInput.value = newType;
          dropdownMenu.classList.add('hidden');
        });
        typeList.prepend(newBtn);

        selectedType.textContent = newType;
        typeInput.value = newType;
        newTypeInput.value = '';
        dropdownMenu.classList.add('hidden');
      }
    });
  });
</script>
@endsection
