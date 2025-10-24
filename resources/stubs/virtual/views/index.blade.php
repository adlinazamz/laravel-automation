@extends('virtual::layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-6 min-h-screen">
  <div class="max-w-screen-xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
      <div>
        <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
          {{ ucfirst($modelName) }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Browse a list of {{ strtolower($modelName) }}
        </p>
      </div>

      <a href="{{ route('virtual.create', ['table' => $modelNameLower]) }}"
         class="inline-flex items-center mt-3 md:mt-0 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
        <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd"
                d="M10 3a1 1 0 011 1v5h5a1 1 0 010 2h-5v5a1 1 0 01-2 0v-5H4a1 1 0 010-2h5V4a1 1 0 011-1z"
                clip-rule="evenodd" />
        </svg>
        New {{ ucfirst($modelName) }}
      </a>
    </div>

    {{-- Table --}}
<div class="flex-grow">
  <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
      <tr>
        <th class="px-2 py-3">ID</th>
        @foreach($fields as $f)
          @if(strtolower($f) !== 'id')
            <th class="px-6 py-3">{{ ucfirst($f) }}</th>
          @endif
        @endforeach
        <th class="px-6 py-3 text-right">Actions</th>
      </tr>
    </thead>

    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
      @forelse ($rows as $i => $row)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
          <td class="px-6 py-4 font-medium">{{ $row->id ?? ('#' . ($i + 1)) }}</td>

          @foreach($fields as $f)
            @if(strtolower($f) !== 'id')
              <td class="px-6 py-4 max-w-xs truncate" title="{{ $row->$f }}">
                {{ Str::limit($row->$f, 60, '...') }}
              </td>
            @endif
          @endforeach

          {{-- Actions --}}
          <td class="px-6 py-4 text-right relative">
            <button type="button"
                    id="dropdownMenuIconButton{{ $row->id }}"
                    data-dropdown-toggle="dropdownMenu{{ $row->id }}"
                    class="p-2 text-center text-gray-600 bg-gray-100 rounded-full hover:bg-gray-200 focus:ring-2 focus:ring-gray-300 
                           dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-600">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 4 15">
                <path d="M3.5 1.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Zm0 6.041a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Zm0 5.959a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0Z" />
              </svg>
            </button>

            {{-- Dropdown --}}
            <div id="dropdownMenu{{ $row->id }}"
                 class="hidden absolute right-0 top-full mt-2 w-44 bg-white divide-y divide-gray-100 rounded-lg shadow-lg 
                        dark:bg-gray-700 dark:divide-gray-600 text-center z-[9999]">
              <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                <li>
                  <a href="{{ route('virtual.show', ['table'=>$modelNameLower,'id'=>$row->id]) }}"
                     class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                    Show
                  </a>
                </li>
                <li>
                  <a href="{{ route('virtual.edit', ['table'=>$modelNameLower,'id'=>$row->id]) }}"
                     class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                    Edit
                  </a>
                </li>
              </ul>
              <div class="py-1 text-red-600">
                <form action="{{ route('virtual.destroy', ['table'=>$modelNameLower,'id'=>$row->id]) }}"
                      method="POST" class="delete-form">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="w-full px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white delete-button"
                          data-name="{{ $row->name ?? '' }}">
                    Delete
                  </button>
                </form>
              </div>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="{{ max(3, count($fields)) + 1 }}"
              class="px-6 py-6 text-center text-gray-400">
            No {{ strtolower($modelName) }} found.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

</div>

    {{-- Pagination --}}
    <div class="p-4 mt-4 text-sm text-gray-600 dark:text-gray-300 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
      <span>
        Showing <strong>{{ $rows->count() }}</strong> of <strong>{{ $rows->total() }}</strong> results
      </span>

      <div class="flex items-center flex-wrap gap-2">
        {{-- Prev --}}
        @if ($rows->onFirstPage())
          <span class="px-3 py-1 text-gray-400 border border-gray-300 rounded-full dark:border-gray-600 dark:text-gray-500">‹ Prev</span>
        @else
          <a href="{{ $rows->previousPageUrl() }}"
             class="px-3 py-1 border border-gray-300 rounded-full hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">
            ‹ Prev
          </a>
        @endif

        {{-- Pages --}}
        @foreach ($rows->getUrlRange(1, $rows->lastPage()) as $page => $url)
          @if ($page == $rows->currentPage())
            <span class="px-3 py-1 bg-blue-600 text-white border border-blue-600 rounded-full">{{ $page }}</span>
          @else
            <a href="{{ $url }}"
               class="px-3 py-1 border border-gray-300 rounded-full hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700">
              {{ $page }}
            </a>
          @endif
        @endforeach

        {{-- Next --}}
        @if ($rows->hasMorePages())
          <a href="{{ $rows->nextPageUrl() }}"
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

@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const name = form.querySelector('.delete-button').dataset.name || 'this {{ $modelNameLower }}';
      swal({
        title: `Delete ${name}?`,
        text: "This action cannot be undone.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      }).then((willDelete) => {
        if (willDelete) form.submit();
      });
    });
  });
});
</script>
@endsection
