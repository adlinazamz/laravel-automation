<!-- CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div 
  x-data="{ collapsed: true, dropdownOpen: false, crudMode: null, hoverTimer: null }"
  @click.away="dropdownOpen = false"
  class="relative min-h-screen bg-gray-100 dark:bg-transparent overflow-hidden"
>
  <!-- Sidebar -->
  <div
    @mouseenter="if(hoverTimer){ clearTimeout(hoverTimer); hoverTimer = null } ; collapsed = false"
    @mouseleave="hoverTimer = setTimeout(()=>{ collapsed = true; dropdownOpen = false }, 160)"
    :class="collapsed ? 'w-16' : 'w-64'"
    class="fixed top-0 left-0 h-screen bg-gray-800 text-white dark:bg-gray-800 dark:text-gray-100 z-40 transition-all duration-300 flex flex-col shadow-lg"
  >
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-4 py-4 ">
      <div x-show="!collapsed" x-transition class="flex items-center space-x-2">
        <span class="text-xl font-bold text-white select-none">Menu</span>
      </div>
      <button @click="collapsed = !collapsed" class="focus:outline-none">
        <svg class="w-6 h-6 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <!-- Nav links -->
    <nav class="flex-1 mt-5 space-y-2 px-1 overflow-y-auto overflow-x-hidden">
      @php
        $navItems = [
          ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5a1 1 0 01-1-1v-5H10v5a1 1 0 01-1 1H4a1 1 0 01-1-1V9.75z'],
          ['label' => 'Products', 'route' => 'products.index', 'icon' => 'M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H4m0 0h16'],
        ];
      @endphp

      @foreach ($navItems as $item)
        @php
          $isActive = request()->routeIs($item['route']);
          $linkClass = $isActive ? 
            'bg-white text-gray-900 font-semibold dark:bg-gray-700 dark:text-gray-100' : 
            'text-gray-300 hover:bg-gray-700 hover:text-white dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white';
        @endphp

        <a href="{{ route(Str::before($item['route'], '.*')) }}"
           class="group relative flex items-center px-4 py-2 gap-x-4 rounded-md transition {{ $linkClass }} overflow-hidden">
          <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
          </svg>
          <span x-show="!collapsed" x-transition class="truncate select-none">{{ $item['label'] }}</span>
          <span x-show="collapsed"
                class="absolute left-full ml-1 bg-gray-700 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap select-none">
            {{ $item['label'] }}
          </span>
        </a>
      @endforeach

      @include('layouts.sidenav-link')
      @stack('sidenav-items')
    </nav>

    <!-- Admin CRUD Button -->
<div class="mt-auto px-4 py-4 border-t border-gray-700 relative">
  <div class="relative">
    <button 
      @click="dropdownOpen = !dropdownOpen"
      class="group relative flex items-center justify-center px-4 py-2 gap-x-4 rounded-md transition text-white bg-blue-600 hover:bg-blue-700 w-full"
    >
      <svg class="w-6 h-6 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
          d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
      </svg>
      <span x-show="!collapsed" x-transition>Admin CRUD</span>
    </button>

    <!-- Dropdown (opens upward) -->
    <div 
  x-show="dropdownOpen"
  x-transition
  class="absolute z-50 bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden"
  :class="collapsed 
    ? 'left-16 w-44 ' 
    : 'left-4 w-52'"
  style="bottom: 100%; transform-origin: bottom center;"
>
  <ul class="p-2 text-sm text-gray-700 dark:text-gray-200 space-y-1">
   
        <!-- Legacy CRUD Option -->
        <li>
          <button 
            @click="crudMode = 'legacy'; dropdownOpen = false"
            class="w-full flex items-start gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 text-left transition"
          >
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" 
                d="M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
            </svg>
            <div class="flex flex-col items-start">
              <span class="font-medium">Legacy CRUD</span>
              <p class="text-xs font-normal text-gray-500 dark:text-gray-300">
                Generate automated MVC API with physical files.
              </p>
            </div>
          </button>
        </li>

        <!-- Virtual CRUD Option -->
        <li>
          <button 
            @click="crudMode = 'virtual'; dropdownOpen = false"
            class="w-full flex items-start gap-3 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 text-left transition"
          >
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" 
                d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <div class="flex flex-col items-start">
              <span class="font-medium">Virtual CRUD</span>
              <p class="text-xs font-normal text-gray-500 dark:text-gray-300">
                Generate automated MVC API with virtual on-the-fly files.
              </p>
            </div>
          </button>
        </li>

      </ul>
    </div>
  </div>
</div>
<!-- Shared CRUD Modal -->
<div 
  x-data="crudModal()"
  x-show="crudMode"
  x-transition
  @keydown.escape.window="crudMode = null"
  x-effect="if (crudMode) { fetchTables(); $nextTick(()=> { if (window.initFlatpickrIn) window.initFlatpickrIn($el); }); }"
  class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50"
  style="display: none;"
>
  <div 
    class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 
           p-6 rounded-2xl shadow-xl w-full max-w-md border border-gray-200 dark:border-gray-700 
           transition-colors duration-200"
  >
    <!-- Header -->
    <h2 
      class="text-lg font-semibold mb-4 flex items-center gap-2"
      x-text="crudMode === 'legacy' ? 'Legacy CRUD' : 'Virtual CRUD'"
    ></h2>

    <!-- Loading State -->
    <template x-if="loading">
      <div class="flex items-center justify-center py-6">
        <svg class="animate-spin h-5 w-5 text-blue-600 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <p class="text-gray-600 dark:text-gray-300">Loading tables...</p>
      </div>
    </template>

    <!-- Table Selector -->
    <template x-if="!loading">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Table</label>
        <select 
          x-model="selectedTable" 
          class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 mb-4 
                 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-100 
                 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
          <option value="">-- Select Table --</option>
          <template x-for="table in tables" :key="table">
            <option :value="table" x-text="table"></option>
          </template>
        </select>
      </div>
    </template>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <button 
        @click="runAutomation(crudMode)" 
        :disabled="!selectedTable"
        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium 
                 transition disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Generate 
        <span x-text="crudMode === 'legacy' ? 'CRUD' : 'Virtual'"></span>
        for 
        <span x-text="selectedTable"></span>
      </button>

      <button 
        @click="crudMode = null" 
        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 
               transition mt-1 sm:mt-0"
      >
        Close
      </button>
    </div>

    <!-- Status Message -->
    <p class="mt-3 text-sm text-blue-600 dark:text-blue-400" x-text="status"></p>
  </div>
</div>
      </div>

<script>
  function crudModal() {
    return {
      tables: [],
      selectedTable: '',
      status: '',
      loading: false,

      fetchTables() {
        this.loading = true;
        this.tables = [];
        this.status = '';

        fetch('/tables')
          .then(res => res.json())
          .then(data => {
            this.tables = data.tables || [];
            this.loading = false;
            if (!this.tables.length) {
              this.status = '⚠️ No tables found.';
            }
          })
          .catch(() => {
            this.status = '❌ Failed to load tables.';
            this.loading = false;
          });
      },

      runAutomation(mode) {
        if (!this.selectedTable) return;
        this.status = 'Running...';
        const endpoint = mode === 'legacy' ? '/run-auto' : '/run-virtual';

        fetch(endpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({ table: this.selectedTable })
        })
        .then(res => res.json())
        .then(data => {
          this.status = data.status === 'done'
            ? '[Done!]'
            : '[Error]: ' + (data.message || 'Unknown error');
        })
        .catch(() => this.status = '[Error]Request failed.');
      }
    };
  }
</script>
