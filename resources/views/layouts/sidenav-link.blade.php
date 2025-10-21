
@push('sidenav-items')

<!-- promotions -->
<a href="{{ route('virtual.index', ['table' => 'promotions']) }}"
      class="group relative flex items-center px-4 py-2 gap-x-4 rounded-md transition
           {{ request()->routeIs('promotions.*') 
               ? 'bg-white text-gray-900 font-semibold' 
               : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
      <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('promotions.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}"
        fill="none"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H4m0 0h16" />
      </svg>

      <span x-show="!collapsed" x-transition class="select-none">promotions</span>

      <span x-show="collapsed"
        class="absolute left-full ml-1 bg-gray-700 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap select-none">
        promotions
      </span>
    </a>
@endpush
@push('sidenav-items')

<!-- events -->
<a href="{{ route('virtual.index', ['table' => 'events']) }}"
      class="group relative flex items-center px-4 py-2 gap-x-4 rounded-md transition
           {{ request()->routeIs('events.*') 
               ? 'bg-white text-gray-900 font-semibold' 
               : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
      <svg class="w-5 h-5 flex-shrink-0 {{ request()->routeIs('events.*') ? 'text-gray-900' : 'text-gray-400 group-hover:text-white' }}"
        fill="none"
        stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
          d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H4m0 0h16" />
      </svg>

      <span x-show="!collapsed" x-transition class="select-none">events</span>

      <span x-show="collapsed"
        class="absolute left-full ml-1 bg-gray-700 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap select-none">
        events
      </span>
    </a>
@endpush