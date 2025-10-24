@extends('products.layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 min-h-screen">
  <div class="mx-auto max-w-screen-md px-4 lg:px-6">
    <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">

      {{-- Header --}}
      <div class="p-5 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
          Add New Product
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Fill out the details below to create a new product.
        </p>
      </div>

      {{-- Form --}}
      <div class="p-6">
        {{-- Back button --}}
        <div class="flex justify-end mb-5">
          <a href="{{ route('products.index') }}"
             class="inline-flex items-center text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg px-3 py-1.5 
                    dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-600 transition">
            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to products
          </a>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="grid gap-4 mb-4 sm:grid-cols-2 sm:gap-6 sm:mb-5">
            
            {{-- Product Name --}}
            <div class="sm:col-span-2">
              <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Name</label>
              <input type="text" name="name" id="name"
                     class="w-full text-sm rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                     placeholder="Type product name" required>
            </div>

            {{-- Product Type --}}
            <div class="w-full relative">
              <label for="type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product Type</label>

              <!-- Dropdown button -->
              <button id="typeDropdownButton"
                      type="button"
                      class="flex justify-between items-center w-full px-3 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 
                             text-gray-700 dark:text-white text-sm rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                      <span id="selectedType">Select or add new product type</span>
                      <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                      </svg>
              </button>
              <input type="hidden" name="type" id="typeInput">

              <!-- Dropdown menu -->
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
                  <div class="border-t border-gray-300 dark:border-gray-700 my-1"></div>

                  <!-- Add new type option -->
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
                        class="block p-2.5 w-full text-sm rounded-lg border border-gray-300 bg-white text-gray-900 placeholder-gray-400 
                               focus:ring-2 focus:ring-blue-500 
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400"
                        placeholder="Write a product description here..."></textarea>
            </div>

            {{-- Image Upload --}}
            <div class="sm:col-span-2">
              <label for="image" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload Image</label>

              <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg overflow-hidden dark:bg-gray-700 dark:border-gray-600">
                  <label for="image" 
                         class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 cursor-pointer whitespace-nowrap">
                      Choose File
                  </label>
                  <input id="image" name="image" type="file" accept="image/*" class="hidden">
                  <span id="file-name" 
                        class="flex-1 text-sm text-gray-500 dark:text-gray-300 px-3 py-2 truncate select-none">
                        No file chosen
                  </span>
              </div>
              <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Accepted: JPG, PNG, GIF (Max: 2MB)</p>
            </div>

          </div>

          {{-- Submit --}}
          <div class="flex items-center space-x-4 mt-5">
            <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-5 py-2.5 transition focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- JS Section --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const fileInput = document.getElementById('image');
  const fileName = document.getElementById('file-name');
  const dropdownButton = document.getElementById('typeDropdownButton');
  const dropdownMenu = document.getElementById('typeDropdownMenu');
  const selectedType = document.getElementById('selectedType');
  const typeInput = document.getElementById('typeInput');
  const addTypeBtn = document.getElementById('addTypeBtn');
  const newTypeInput = document.getElementById('newTypeInput');
  const typeList = document.getElementById('typeList');

  // File name display
  fileInput.addEventListener('change', e => {
    fileName.textContent = e.target.files[0]?.name || 'No file chosen';
  });

  // Dropdown toggle
  dropdownButton.addEventListener('click', e => {
    e.stopPropagation();
    dropdownMenu.classList.toggle('hidden');
  });

  // Click outside to close
  document.addEventListener('click', e => {
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

  // Add new type dynamically
  addTypeBtn.addEventListener('click', () => {
    const newType = newTypeInput.value.trim();
    if (!newType) return;

    const newBtn = document.createElement('button');
    newBtn.type = 'button';
    newBtn.className = 'w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 select-type';
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
  });
});
</script>
@endsection
