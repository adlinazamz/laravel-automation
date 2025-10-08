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

    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
      class="group relative flex items-center px-4 py-2 gap-x-4 rounded-md transition
           {{ request()->routeIs('dashboard') 
               ? 'bg-white text-gray-900 font-semibold' 
               : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
      <svg class="w-5 h-5 flex-shrink-0"
        fill="none"
        :class="`${
          request()->routeIs('dashboard') 
            ? 'text-gray-900' 
            : 'text-gray-400 group-hover:text-white'
        }`"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M3 9.75L12 3l9 6.75V20a1 1 0 01-1 1h-5a1 1 0 01-1-1v-5H10v5a1 1 0 01-1 1H4a1 1 0 01-1-1V9.75z" />
      </svg>

      <!-- Description slides out next to icon -->
      <span x-show="!collapsed" x-transition class="select-none">Dashboard</span>

      <!-- Tooltip on collapse -->
      <span x-show="collapsed"
        class="absolute left-full ml-1 bg-gray-700 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap select-none">
        Dashboard
      </span>
    </a>

    <!-- Products -->
    <a href="{{ route('products.index') }}"
      class="group relative flex items-center px-4 py-2 gap-x-4 rounded-md transition
           {{ request()->routeIs('products.*') 
               ? 'bg-white text-gray-900 font-semibold' 
               : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
      <svg class="w-5 h-5 flex-shrink-0"
        fill="none"
        :class="`${
          request()->routeIs('products.*') 
            ? 'text-gray-900' 
            : 'text-gray-400 group-hover:text-white'
        }`"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H4m0 0h16" />
      </svg>

      <span x-show="!collapsed" x-transition class="select-none">Products</span>

      <span x-show="collapsed"
        class="absolute left-full ml-1 bg-gray-700 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap select-none">
        Products
      </span>
    </a>

    @include('layouts.sidenav-link')
    @stack('sidenav-items')

  </nav>
</div>

<!-- Content area -->
<div
  class="transition-all duration-300"
  style="margin-left: 4rem;"  
>

</div>