@extends('products.layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-6 min-h-screen">
  <div class="max-w-screen-xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
      <div>
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Products</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Browse and manage your products</p>
      </div>
      <a href="{{ route('products.create') }}" 
         class="inline-flex items-center mt-3 md:mt-0 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 010 2h-5v5a1 1 0 01-2 0v-5H4a1 1 0 010-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
        </svg>
        New Product
      </a>
    </div>

    {{-- Filter & Tools --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-5">
      <div class="flex gap-3">
        {{-- Filter --}}
<div class="relative">
  <button id="filterDropdownButton"
    class="flex items-center justify-between w-36 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600">
    <span>Filter</span>
    <svg class="w-4 h-4 ml-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
    </svg>
  </button>

  <div id="filterDropdown"
       class="hidden absolute z-50 mt-2 w-72 p-4 bg-white border border-gray-200 rounded-lg shadow-lg 
              dark:bg-gray-800 dark:border-gray-700">
    <form method="GET" action="{{ route('products.index') }}" class="space-y-4">

        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filter Update Date</label>

      {{-- Start Date --}}
      <div>
        <label for="start" 
               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
          Start date
        </label>
        <input id="start" name="start" type="date"
               value="{{ request('start') }}"
               class="w-full px-3 py-2 mt-1 text-sm bg-gray-50 border border-gray-300 rounded-lg 
                      focus:ring-blue-500 focus:border-blue-500 
                      dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
      </div>

      {{-- End Date --}}
      <div>
        <label for="end" 
               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
          End date
        </label>
        <input id="end" name="end" type="date"
               value="{{ request('end') }}"
               class="w-full px-3 py-2 mt-1 text-sm bg-gray-50 border border-gray-300 rounded-lg 
                      focus:ring-blue-500 focus:border-blue-500 
                      dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
      </div>

      {{-- Buttons --}}
      <div class="flex justify-between pt-2">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
          Apply
        </button>
        <a href="{{ route('products.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition">
          Reset
        </a>
      </div>
    </form>
  </div>
</div>


        {{-- Tools --}}
        <div class="relative">
          <button id="toolsDropdownButton" 
            class="flex items-center justify-between w-36 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600">
            <span>Import/Export</span>
            <svg class="w-4 h-4 ml-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
          </button>

          <div id="toolsDropdown" 
               class="hidden absolute z-50 mt-2 w-72 p-4 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
            <form id="import-form" action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
              @csrf
              <div>
      <label class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-1">Upload File</label>

      <div class="flex items-center bg-gray-700 border border-gray-600 rounded-lg overflow-hidden">
        <label for="file" 
               class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 cursor-pointer whitespace-nowrap">
          Choose File
        </label>

        <input id="file" name="file" type="file" accept=".xlsx,.csv"
               class="hidden"
               onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'No file chosen'">

        <span id="file-name" 
              class="flex-1 text-sm text-gray-300 px-3 py-2 truncate select-none">
          No file chosen
        </span>
      </div>

      <p class="mt-1 text-xs text-gray-400">Accepted: CSV, XLSX</p>
    </div>

              <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm w-full">
                  Import
                </button>
                <a href="{{ route('products.export') }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm w-full text-center">
                  Export
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    {{-- Table --}}
    <div class="flex-grow overflow-x-auto">
      @if (session('success'))
        <div class="p-4 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border-b border-gray-200 dark:border-gray-700">
          {{ session('success') }}
        </div>
      @endif

      <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
          <tr>
            <th class="px-6 py-3">No</th>
            <th class="px-6 py-3">Image</th>
            <th class="px-6 py-3">Name</th>
            <th class="px-6 py-3">Details</th>
            <th class="px-6 py-3">Type</th>
            <th class="px-6 py-3">Updated</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
          @forelse ($products as $product)
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <td class="px-6 py-4">{{ ++$i }}</td>
            <td class="px-6 py-4">
              <img 
                src="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}" 
                class="w-10 h-auto rounded cursor-pointer open-modal"
                data-img="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}" 
              />
            </td>
            <td class="px-6 py-4 font-medium">{{ $product->name }}</td>
            <td class="px-6 py-4 max-w-xs">
              <p class="truncate-text">{{ Str::limit($product->detail, 100) }}</p>
              @if(strlen($product->detail) > 100)
                <button class="text-blue-600 dark:text-blue-400 text-xs mt-1 toggle-detail" 
                        data-full="{{ $product->detail }}">
                  Read more
                </button>
              @endif
            </td>
            <td class="px-6 py-4">{{ $product->type }}</td>
            <td class="px-6 py-4">{{ $product->updated_at->format('d M Y') }}</td>
            <td class="px-6 py-4 text-right relative">
              <button type="button" id="dropdownButton{{ $product->id }}" 
                class="p-2 text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 4 15">
                  <path d="M3.5 1.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Zm0 6.041a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Zm0 5.959a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Z"/>
                </svg>
              </button>
              <div id="dropdownMenu{{ $product->id }}" 
                   class="hidden absolute right-0 z-[9999] mt-2 w-44 bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-gray-700 dark:divide-gray-600 text-center">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                  <li><a href="{{ route('products.show', $product->id) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Show</a></li>
                  <li><a href="{{ route('products.edit', $product->id) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Edit</a></li>
                </ul>
                <div class="py-1 text-red-600">
                  <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="w-full px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white delete-button"
                      data-name="{{ $product->name }}">
                      Delete
                    </button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="px-6 py-6 text-center text-gray-400">No products found.</td></tr>
          @endforelse
        </tbody>
      </table>

      {{-- Pagination --}}
    <div class="p-4 mt-4 text-sm text-gray-600 dark:text-gray-300 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
      <span>
        Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> results
      </span>

      <div class="flex items-center flex-wrap gap-2">
        {{-- Prev --}}
        @if ($products->onFirstPage())
          <span class="px-3 py-1 text-gray-400 border border-gray-300 rounded-full dark:border-gray-600 dark:text-gray-500">‹ Prev</span>
        @else
          <a href="{{ $products->previousPageUrl() }}"
             class="px-3 py-1 border border-gray-300 rounded-full hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">
            ‹ Prev
          </a>
        @endif

        {{-- Pages --}}
        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
          @if ($page == $products->currentPage())
            <span class="px-3 py-1 bg-blue-600 text-white border border-blue-600 rounded-full">{{ $page }}</span>
          @else
            <a href="{{ $url }}"
               class="px-3 py-1 border border-gray-300 rounded-full hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">
              {{ $page }}
            </a>
          @endif
        @endforeach

        {{-- Next --}}
        @if ($products->hasMorePages())
          <a href="{{ $products->nextPageUrl() }}"
             class="px-3 py-1 border border-gray-300 rounded-full hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">
            Next ›
          </a>
        @else
          <span class="px-3 py-1 text-gray-400 border border-gray-300 rounded-full dark:border-gray-600 dark:text-gray-500">Next ›</span>
        @endif
      </div>
    </div>

  </div>
</div>

</div>

{{-- Image Modal --}}
<div id="imageModal"
     class="fixed inset-0 bg-black/80 backdrop-bklur-smflex items-center justify-center z-50 hidden">
  <div class="relative flex justify-center items-center p-6">
    <!-- Image container -->
    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden flex flex-col items-center justify-center w-[90vw] max-w-[720px] h-auto p-6">
      <!-- Close Button -->
      <button id="closeModal"
              class="absolute top-3 right-3 z-10 bg-black/50 text-white rounded-full p-2 hover:bg-black/70 transition">
        x
      </button>

      <!-- Image -->
      <div class = "flex justify-center items-center w-full h-[60vh] bg-gray-50 dark:bg:gray-700 rounded-xl p-4">
        <img id="modalImage"
           src=""
           class="w-full h-full object-contain rounded-lg"
           alt="Product Image">
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Dropdowns
  function setupDropdown(buttonId, dropdownId) {
    const button = document.getElementById(buttonId);
    const dropdown = document.getElementById(dropdownId);
    if (!button || !dropdown) return;

    // Enhanced dropdown: append to body when opened to avoid clipping by table or overflow:hidden
    // and position near the button using fixed coordinates.
    button.addEventListener('click', (e) => {
      e.stopPropagation();
      // toggle
      const isHidden = dropdown.classList.contains('hidden');
      if (isHidden) {
        // store original parent to restore later
        if (!dropdown.dataset._origParent) {
          dropdown.dataset._origParent = dropdown.parentNode ? dropdown.parentNode.tagName : '';
          dropdown.dataset._origIndex = Array.prototype.indexOf.call(dropdown.parentNode ? dropdown.parentNode.children : [], dropdown);
        }

        // Move to body
        document.body.appendChild(dropdown);
        dropdown.style.position = 'fixed';
        dropdown.style.zIndex = 9999;
        dropdown.classList.remove('hidden');

        // position: align right edge of dropdown with right edge of button
        const rect = button.getBoundingClientRect();
        // allow dropdown to measure itself
        dropdown.style.left = '0px';
        dropdown.style.top = '-9999px';
        dropdown.classList.remove('invisible');
        // small timeout to ensure layout
        setTimeout(() => {
          const ddRect = dropdown.getBoundingClientRect();
          const left = Math.max(8, rect.right - ddRect.width);
          const top = rect.bottom + 6; // small gap
          dropdown.style.left = left + 'px';
          dropdown.style.top = top + 'px';
        }, 0);
      } else {
        // hide and try to restore original positioning
        dropdown.classList.add('hidden');
        dropdown.style.position = '';
        dropdown.style.left = '';
        dropdown.style.top = '';
        // try to place back into its original container (best-effort)
        try {
          // if original parent tagName stored, find nearest matching container in table cell
          const targetCell = document.getElementById(buttonId).closest('td') || document.getElementById(buttonId).closest('tr');
          if (targetCell) {
            // find dropdown element inside that cell's content
            const placeholder = targetCell.querySelector('#' + dropdownId);
            if (!placeholder) {
              targetCell.appendChild(dropdown);
            }
          }
        } catch (ex) {}
      }
    });

    // global click to close
    document.addEventListener('click', (e) => {
      if (!button.contains(e.target) && !dropdown.contains(e.target)) {
        if (!dropdown.classList.contains('hidden')) {
          dropdown.classList.add('hidden');
          dropdown.style.position = '';
          dropdown.style.left = '';
          dropdown.style.top = '';
          // attempt to move back near button's cell
          try {
            const targetCell = document.getElementById(buttonId).closest('td') || document.getElementById(buttonId).closest('tr');
            if (targetCell && !targetCell.querySelector('#' + dropdownId)) {
              targetCell.appendChild(dropdown);
            }
          } catch (ex) {}
        }
      }
    });
  }

  document.querySelectorAll('[id^="dropdownButton"]').forEach(btn => {
    const id = btn.id.replace('dropdownButton', '');
    setupDropdown(btn.id, 'dropdownMenu' + id);
  });

  setupDropdown('filterDropdownButton', 'filterDropdown');
  setupDropdown('toolsDropdownButton', 'toolsDropdown');

  // SweetAlert delete
  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      const name = form.querySelector('.delete-button').dataset.name || 'this product';
      swal({
        title: `Delete ${name}?`,
        text: "This action cannot be undone.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then(willDelete => {
        if (willDelete) form.submit();
      });
    });
  });
//image modal
  const modal = document.getElementById('imageModal');
  const modalImg = document.getElementById('modalImage');
  const closeModal = document.getElementById('closeModal');

  document.querySelectorAll('.open-modal').forEach(img => {
    img.addEventListener('click', function() {
      modalImg.src = this.dataset.img;
      modal.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
    });
  });

  closeModal.addEventListener('click', () => {
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
  });

  modal.addEventListener('click', e => {
    if (e.target === modal) {
      modal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }
  });

  // Read more toggle
  document.querySelectorAll('.toggle-detail').forEach(btn => {
    btn.addEventListener('click', () => {
      const p = btn.previousElementSibling;
      if (btn.textContent === 'Read more') {
        p.textContent = btn.dataset.full;
        btn.textContent = 'Show less';
      } else {
        p.textContent = btn.dataset.full.substring(0, 100) + '...';
        btn.textContent = 'Read more';
      }
    });
  });
});
</script>
@endsection
