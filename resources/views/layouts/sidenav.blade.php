<!-- CSRF token for JS fetch -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Sidebar -->
<div
  x-data="{ collapsed: true }"
  x-bind:class="collapsed  
    ? 'fixed top-0 left-0 w-16 bg-gray-800 z-40 overflow-hidden'  
    : 'fixed top-0 left-0 w-50 bg-gray-800 z-40 overflow-y-auto'"
  class="min-h-screen h-full text-white transition-all duration-300"
  x-cloak
  @mouseenter="collapsed = false"
  @mouseleave="collapsed = true"
>
  <!-- Sidebar header -->
  <div class="flex items-center justify-between px-4 py-4">
    <div class="flex items-center space-x-2" x-show="!collapsed" x-transition>
      <span class="text-xl font-bold text-white select-none">Menu</span>
    </div>
    <button @click="collapsed = !collapsed" class="focus:outline-none">
      <svg class="w-6 h-6 text-gray-400 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <!-- Nav links -->
  <nav class="mt-5 space-y-4 px-1">
    @php
      $navItems = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5a1 1 0 01-1-1v-5H10v5a1 1 0 01-1 1H4a1 1 0 01-1-1V9.75z'],
        ['label' => 'Products', 'route' => 'products.index', 'icon' => 'M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H4m0 0h16'],
      ];
    @endphp

    @foreach ($navItems as $item)
      @php
        $isActive = request()->routeIs($item['route']);
        $linkClass = $isActive ? 'bg-white text-gray-900 font-semibold' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
        $iconClass = $isActive ? 'text-gray-900' : 'text-gray-400 group-hover:text-white';
      @endphp

      <a href="{{ route(Str::before($item['route'], '.*')) }}"
        class="group relative flex items-center px-4 py-2 gap-x-4 rounded-md transition {{ $linkClass }}">
        <svg class="w-5 h-5 flex-shrink-0 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
        </svg>
        <span x-show="!collapsed" x-transition class="select-none">{{ $item['label'] }}</span>
        <span x-show="collapsed"
          class="absolute left-full ml-1 bg-gray-700 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap select-none">
          {{ $item['label'] }}
        </span>
      </a>
    @endforeach

    @include('layouts.sidenav-link')
    @stack('sidenav-items')
  </nav>

  <!-- Footer with automation buttons -->
  <div class="mt-auto px-4 py-4 border-t border-gray-700 space-y-2">
    
    <!-- Dev CRUD trigger -->
    <button 
        x-data 
        @click="$dispatch('open-dev-crud')"
        class="w-full px-2 py-2 text-white bg-indigo-600 hover:bg-indigo-700 rounded-md"
    >
        ⚙️ Dev CRUD
    </button>
  </div>
</div><!-- close sidebar -->

<!-- Dev CRUD Modal -->
<div 
    x-data="devCrud()" 
    x-show="open" 
    @open-dev-crud.window="open = true; fetchTables()" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;"
>
    <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Dev CRUD Automation</h2>

        <select x-model="selectedTable" class="w-full border rounded px-2 py-1 mb-4" x-show="!loading">
            <option value="">-- Select Table --</option>
            <template x-for="table in tables" :key="table">
                <option :value="table" x-text="table"></option>
            </template>
        </select>

        <button 
            @click="runAutomation" 
            :disabled="!selectedTable"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-50"
        >
            Generate CRUD for <span x-text="selectedTable"></span>
        </button>

        <p class="mt-2 text-sm text-blue-600" x-text="status"></p>
        <button @click="open = false" class="mt-4 text-sm text-gray-500 hover:underline">Close</button>
    </div>
</div>

<!-- Alpine component script -->
<script>
    function devCrud() {
        return {
            open: false,
            tables: [],
            selectedTable: '',
            status: '',
            loading: true,

            fetchTables() {
                this.loading = true;
                fetch('/tables')
                    .then(res => res.json())
                    .then(data => {
                        this.tables = data.tables;
                        this.loading = false;
                    })
                    .catch(err => {
                        this.status = '❌ Failed to load tables';
                        console.error(err);
                    });
            },

            runAutomation() {
                if (!this.selectedTable) return;
                this.status = 'Running...';

                fetch('/run-auto', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ table: this.selectedTable })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Network response was not ok');
                    return res.json();
                })
                .then(data => {
                    this.status = data.status === 'done' ? '✅ Done!' : '❌ Error: ' + data.message;
                })
                .catch(err => {
                    this.status = '❌ Failed to run automation';
                    console.error(err);
                });
            }
        };
    }
</script>