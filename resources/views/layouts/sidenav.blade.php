<!-- CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div 
  x-data="{ collapsed: true }"
  class="relative min-h-screen bg-gray-100 overflow-hidden"
>
  <!-- Sidebar -->
  <div
    @mouseenter="collapsed = false"
    @mouseleave="collapsed = true"
    :class="collapsed ? 'w-16' : 'w-64'"
    class="fixed top-0 left-0 h-screen bg-gray-800 text-white z-40 transition-all duration-300 flex flex-col shadow-lg overflow-hidden"
    x-cloak
  >
    <!-- Sidebar header -->
    <div class="flex items-center justify-between px-4 py-4 border-b border-gray-700">
      <div x-show="!collapsed" x-transition class="flex items-center space-x-2">
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
          $linkClass = $isActive ? 'bg-white text-gray-900 font-semibold' : 'text-gray-300 hover:bg-gray-700 hover:text-white';
          $iconClass = $isActive ? 'text-gray-900' : 'text-gray-400 group-hover:text-white';
        @endphp

        <a href="{{ route(Str::before($item['route'], '.*')) }}"
          class="group relative flex items-center px-4 py-2 gap-x-4 rounded-md transition {{ $linkClass }} overflow-hidden">
          <svg class="w-5 h-5 flex-shrink-0 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- Footer buttons -->
    <div class="mt-auto px-4 py-4 border-t border-gray-700 space-y-2">
      @php
        $crudItems = [
          ['label' => 'Legacy CRUD', 'event' => 'open-legacy-crud', 'icon' => 'M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z'],
          ['label' => 'Virtual CRUD', 'event' => 'open-vir-crud', 'icon' => 'M12 4.5v15m7.5-7.5h-15'],
        ];
      @endphp

      @foreach ($crudItems as $item)
        <button 
          @click="$dispatch('{{ $item['event'] }}')"
          class="group relative flex items-center justify-center px-4 py-2 gap-x-4 rounded-md transition text-white bg-blue-600 hover:bg-blue-700 w-full"
        >
          <svg class="w-6 h-6 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
          </svg>
          <span x-show="!collapsed" x-transition class="select-none">{{ $item['label'] }}</span>
        </button>
      @endforeach
    </div>
  </div>

</div>


<!-- Legacy CRUD Modal -->
<div 
    x-data="crudModal('legacy')" 
    x-show="open" 
    @open-legacy-crud.window="open = true; fetchTables()" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;"
>
    <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Legacy CRUD</h2>

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
<!-- virtual CRUD Modal -->
<div 
    x-data="crudModal('virtual')" 
    x-show="open" 
    @open-vir-crud.window="open = true; fetchTables()" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display: none;"
>
    <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">Virtual CRUD</h2>

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
            Generate Virtual for <span x-text="selectedTable"></span>
        </button>

        <p class="mt-2 text-sm text-blue-600" x-text="status"></p>
        <button @click="open = false" class="mt-4 text-sm text-gray-500 hover:underline">Close</button>
    </div>
</div>

<!-- Alpine component script -->
<script>
    function crudModal(mode = 'legacy') {
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

                const endpoint = mode === 'legacy' ? '/run-auto' : '/run-virtual';

                fetch(endpoint, {
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
                    this.status = data.status === 'done' ? '✅ Done!' : ' Error: '+data.message;
                });
            }
        };
    }
</script>