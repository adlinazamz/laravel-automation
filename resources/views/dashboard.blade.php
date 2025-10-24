<x-app-layout>

  <script>
  const productTypeLabels = @json($typeCounts->keys());
  const productTypeData = @json($typeCounts->values());
    // productCreated/Updated are now keyed by ISO date (Y-m-d) in controller
    const productCreated = @json(array_values($productCreated));
    const productUpdated = @json(array_values($productUpdated));
    const productDatesRaw = @json(array_keys($productCreated)); // ISO keys like 2025-10-23
    const productDates = productDatesRaw.map(d => {
      try { const dt = new Date(d); if (!isNaN(dt)) return dt.toLocaleDateString(undefined, { day: 'numeric', month: 'short' }); } catch (e) {}
      return d;
    });
  </script>

  <div class="min-h-screen py-6 bg-gray-50 dark:bg-gray-900 bg-white">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
          <h5 class="text-gray-500 dark:text-gray-400 mb-2">Total Products</h5>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{$products->count()}}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
          <h5 class="text-gray-500 dark:text-gray-400 mb-2">New Products Created Today</h5>
          <p class="text-2xl font-bold text-gray-900 dark:text-white">{{$newProducts}}</p>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- Line Chart (2 columns wide) -->
        <div class="md:col-span-2 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
          <div class="flex justify-between items-start mb-5">
            <div class="grid grid-cols-2 gap-6">
              <div>
                <h5 class="text-gray-500 dark:text-gray-400 text-sm mb-1">Created</h5>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ array_sum($productCreated) }}</p>
              </div>
              <div>
                <h5 class="text-gray-500 dark:text-gray-400 text-sm mb-1">Updated</h5>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ array_sum($productUpdated) }}</p>
              </div>
            </div>

            <!-- Range Dropdown -->
            @php
              $labels = [
                'today' => 'Today',
                'yesterday' => 'Yesterday',
                '7' => 'Last 7 days',
                '30' => 'Last 30 days',
                '90' => 'Last 90 days',
              ];
              $currentLabel = $labels[$range] ?? 'Last 7 days';
            @endphp

            <div>
              <button id="dropdownDefaultButton"
                data-dropdown-toggle="lastDaysdropdown"
                data-dropdown-placement="bottom"
                type="button"
                class="px-3 py-2 inline-flex items-center text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-blue-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                {{ $currentLabel }}
                <svg class="w-2.5 h-2.5 ms-2.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
                </svg>
              </button>

              <div id="lastDaysdropdown"
                class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200">
                  <li><a href="{{ route('dashboard', ['range' => 'today']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Today</a></li>
                  <li><a href="{{ route('dashboard', ['range' => 'yesterday']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Yesterday</a></li>
                  <li><a href="{{ route('dashboard', ['range' => '7']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Last 7 days</a></li>
                  <li><a href="{{ route('dashboard', ['range' => '30']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Last 30 days</a></li>
                  <li><a href="{{ route('dashboard', ['range' => '90']) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600">Last 90 days</a></li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Line Chart -->
          <div id="line-chart" class="w-full h-96"></div>

          <!-- Export Button -->
          <div class="border-t border-gray-200 dark:border-gray-700 mt-5 pt-5 flex justify-end">
            <a id="exportReportButton" data-range="{{ $range ?? '7' }}"
              class="px-5 py-2.5 text-sm font-medium text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              <svg class="w-3.5 h-3.5 text-white me-2" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20">
                <path d="M14.066 0H7v5a2 2 0 0 1-2 2H0v11a1.97 1.97 0 0 0 1.934 2h12.132A1.97 1.97 0 0 0 16 18V2a1.97 1.97 0 0 0-1.934-2ZM9 5V0L5 4h4Z"/>
              </svg>
              View Full Report
            </a>
          </div>
        </div>
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
          <div class="flex items-start justify-between mb-4">
            <h5 class="text-xl font-bold text-gray-900 dark:text-white">Product Type</h5>
            <div>
        <button type="button" data-tooltip-target="data-tooltip" data-tooltip-placement="bottom" class="hidden sm:inline-flex items-center justify-center text-gray-500 w-8 h-8 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm">
          <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 18">
    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 1v11m0 0 4-4m-4 4L4 8m11 4v3a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-3"/>
  </svg><span class="sr-only">Download data</span>
        </button>
        <div id="data-tooltip" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-xs opacity-0 tooltip dark:bg-gray-700">
            Download CSV
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
      </div>
          </div>
          <div id="donut-chart" class="w-full h-80"></div>
        </div>

      </div>
    </div>
  </div>
</x-app-layout>
