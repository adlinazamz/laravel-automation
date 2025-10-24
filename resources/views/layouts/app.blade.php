<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Early theme init to avoid flash of white when dark mode preferred -->
    <script>
        !(function(){
            try {
                var t = localStorage.getItem('theme');
                if (t === 'dark' || (!t && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch(e){}
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind + App -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Flatpickr (Datepicker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    @stack('styles')
</head>

<body class="h-full font-sans antialiased bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
<div x-data="{ sidebarOpen: false, collapsed: false }" class="relative bg-gray-100 dark:bg-gray-900">
        
    <!-- Mobile Overlay -->
    <div 
        x-show="sidebarOpen"
        x-cloak
        class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden"
        @click="sidebarOpen = false"
    ></div>

    <!-- Sidebar Include -->
    @include('layouts.sidenav')

    <!-- Main Content Area -->
  <div class="flex-1 flex flex-col overflow-hidden transition-all duration-300 dark:bg-gray-900 bg-white"
     :class="collapsed ? 'ml-16' : 'ml-64'">

        <!-- Topbar -->
        <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="w-6 h-6"></div>
<!--WIP-->
            <!-- Right: Auth user dropdown -->
            <div class="flex items-center space-x-4">
                <!-- Theme toggle -->
                <!--<button id="theme-toggle" aria-label="Toggle theme" -->
                    <!--    class="inline-flex items-center p-2 rounded-md text-gray-600 bg-white hover:text-gray-800 dark:bg-gray-700 dark:text-gray-200">-->
                    <!-- Light icon -->
                    <!--<svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">-->
                     <!--   <path d="M10 3.22a.75.75 0 01.75-.72h0a.75.75 0 01.75.72V5a.75.75 0 01-.75.72h0A.75.75 0 0110 5V3.22zM10 15.78a.75.75 0 01.75.72v1.78a.75.75 0 01-.75.72h0a.75.75 0 01-.75-.72v-1.78c0-.4.34-.72.75-.72zM3.22 10a.75.75 0 01-.72-.75v0a.75.75 0 01.72-.75H5a.75.75 0 01.72.75v0A.75.75 0 015 10H3.22zM15.78 10a.75.75 0 01.72-.75h0a.75.75 0 01.72.75V10a.75.75 0 01-.72.75h0A.75.75 0 0115.78 10zM5.64 5.64a.75.75 0 01.53-.22h0a.75.75 0 01.53 1.28L6.1 6.54a.75.75 0 01-1.06-1.06l.6-.6zM13.9 13.9a.75.75 0 01.53-.22h0a.75.75 0 01.53 1.28l-.6.6a.75.75 0 01-1.06-1.06l.6-.6zM5.64 14.36a.75.75 0 01.53-.22h0a.75.75 0 01.53 1.28l-.6.6a.75.75 0 01-1.06-1.06l.6-.6zM13.9 6.1a.75.75 0 01.53-.22h0a.75.75 0 01.53 1.28l-.6.6a.75.75 0 01-1.06-1.06l.6-.6z"/>-->
                    <!--</svg>-->
                    <!-- Dark icon -->
                    <!--<svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">-->
                       <!-- <path d="M17.293 13.293A8 8 0 116.707 2.707a7 7 0 1010.586 10.586z"/>-->
                    <!--</svg>-->
               <!-- </button>-->

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 dark:bg-transparent dark:text-gray-200">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="ml-1 w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.66a.75.75 0 01-1.08 0l-4.25-4.66a.75.75 0 01.02-1.06z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                                 onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>
        </header>

  <!-- Page Content -->
  <!-- Remove inner overflow to avoid double scrollbars; let the document body handle scrolling -->
  <main class="flex-1 p-6">
            @if (isset($header))
                <header class="bg-white shadow dark:bg-transparent">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>

@yield('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Guard: flatpickr must be loaded
  if (typeof flatpickr === 'undefined') {
    console.warn('flatpickr not found; datepicker will not initialize.');
    return;
  }

  const darkMode = document.documentElement.classList.contains('dark');

  const fpOptionsBase = {
    dateFormat: "Y-m-d",
    allowInput: true,
    defaultDate: null,
    // when opened, ensure dark theme class is applied to the calendar container
    onOpen: function(selectedDates, dateStr, instance) {
      const cal = instance.calendarContainer;
      if (darkMode) cal.classList.add('flatpickr-dark');
      else cal.classList.remove('flatpickr-dark');
    }
  };
  function initFlatpickrOn(el) {
    if (!el || el.dataset.fpInit === 'true') return;
    // determine if we need time
    const enableTime = el.getAttribute('data-enable-time') === 'true';
    // allow per-input display format via data-alt-format (e.g. 'd/m/Y') while keeping internal ISO storage
    const altFormat = el.getAttribute('data-alt-format') || 'd/m/Y';
    const opts = Object.assign({}, fpOptionsBase, {
      altInput: true,
      altFormat: altFormat
    }, enableTime ? { enableTime: true, time_24hr: true, dateFormat: "Y-m-d H:i", altFormat: el.getAttribute('data-alt-format') || 'd/m/Y H:i' } : {});
    try {
      flatpickr(el, opts);
      el.dataset.fpInit = 'true';
    } catch (e) {
      console.error('flatpickr init error for', el, e);
    }
  }

  // initialize any existing inputs (be permissive: include inputs that may be missing the data-fp-init attr)
  // Also auto-tag inputs that look like date fields (name/id/placeholder containing 'date')
  // Only target inputs likely to be date fields to avoid heavy scanning
  document.querySelectorAll('input[name*="date"], input[id*="date"], input[placeholder*="date"]').forEach(function(inp){
    try {
      if (!inp.classList.contains('datepicker')) inp.classList.add('datepicker');
      if (!inp.hasAttribute('data-fp-init')) inp.setAttribute('data-fp-init','false');
    } catch (e) {}
  });

  document.querySelectorAll('.datepicker').forEach(initFlatpickrOn);

  // Expose a lightweight helper so other scripts/components can initialize
  // flatpickr inside a specific root element (useful for modals or dynamically
  // injected virtual content). Usage: window.initFlatpickrIn(elementOrDocument)
  window.initFlatpickrIn = function(root) {
    try {
      const scope = root || document;
      if (!scope || !scope.querySelectorAll) return;
      scope.querySelectorAll('input[name*="date"], input[id*="date"], input[placeholder*="date"], .datepicker').forEach(function(inp){
        try {
          if (!inp.classList.contains('datepicker')) inp.classList.add('datepicker');
          if (!inp.hasAttribute('data-fp-init')) inp.setAttribute('data-fp-init','false');
        } catch (e) {}
      });
      scope.querySelectorAll('.datepicker').forEach(initFlatpickrOn);
    } catch (e) {}
  };

  // MutationObserver to catch injected/generated form fields (virtual)
  // Throttle work and aggregate mutations to avoid re-entrancy or CPU spikes when large subtrees are added.
  const __mutationBuffer = [];
  let __mutationTimer = null;
  let __mutationProcessing = false;

  function __processMutationBuffer() {
    if (__mutationProcessing) return;
    __mutationProcessing = true;
    // Drain buffer into a local array and clear global buffer to avoid growing memory
    const muts = __mutationBuffer.splice(0, __mutationBuffer.length);
    // Use a Set to avoid handling the same node many times
    const nodeSet = new Set();
    for (const m of muts) {
      if (!m.addedNodes || m.addedNodes.length === 0) continue;
      for (const n of m.addedNodes) {
        if (n && n.nodeType === 1) nodeSet.add(n);
      }
    }

    const MAX_NODES = 200;

    // If a huge number of nodes were added in one burst, avoid traversing them all
    // (this often happens when virtual pages inject large DOM trees). In that case
    // perform a targeted scan for likely date inputs across the document instead
    // of walking every added node which can freeze the main thread.
    if (nodeSet.size > MAX_NODES) {
      try {
        console.warn('Large DOM mutation burst detected (nodes=' + nodeSet.size + '). Performing targeted scan for date inputs instead of full subtree processing.');
      } catch (e) {}
      try {
        document.querySelectorAll('input[name*="date"], input[id*="date"], input[placeholder*="date"], .datepicker').forEach(function(inp){
          if (!inp.classList.contains('datepicker')) inp.classList.add('datepicker');
          if (!inp.hasAttribute('data-fp-init')) inp.setAttribute('data-fp-init','false');
        });
        document.querySelectorAll('.datepicker').forEach(initFlatpickrOn);
      } catch (e) {}

      __mutationProcessing = false;
      return;
    }

    nodeSet.forEach(node => {
      try {
        // skip flatpickr's own calendar nodes
        if (node.classList && node.classList.contains('flatpickr-calendar')) return;
      } catch (e) {}

      try {
        if (node.matches && node.matches('.datepicker') && node.dataset.fpInit !== 'true') {
          initFlatpickrOn(node);
        }
      } catch (e) {}

      // Only search likely date inputs inside the subtree to limit work
      try {
        node.querySelectorAll && node.querySelectorAll('input[name*="date"], input[id*="date"], input[placeholder*="date"]').forEach(function(inp){
          if (!inp.classList.contains('datepicker')) inp.classList.add('datepicker');
          if (!inp.hasAttribute('data-fp-init')) inp.setAttribute('data-fp-init','false');
        });
      } catch (e) {}

      try {
        node.querySelectorAll && node.querySelectorAll('.datepicker').forEach(initFlatpickrOn);
      } catch (e) {}
    });

    __mutationProcessing = false;
  }

  const observer = new MutationObserver((mutations) => {
    // Collect mutations and schedule a single processing pass (debounced)
    __mutationBuffer.push(...mutations);
    if (__mutationTimer) clearTimeout(__mutationTimer);
    __mutationTimer = setTimeout(() => {
      __processMutationBuffer();
      __mutationTimer = null;
    }, 120);
  });

  // Observe body for added nodes only; attribute changes are not required for datepicker init
  observer.observe(document.body, { childList: true, subtree: true });

  // Toggle button (calendar icon) focusing the input to open the picker
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.datepicker-toggle');
    if (!btn) return;
    const targetSelector = btn.getAttribute('data-target');
    let input;
    if (targetSelector) {
      input = document.querySelector(targetSelector);
    } else {
      // fallback: find previous input in same wrapper
      input = btn.closest('.relative')?.querySelector('.datepicker');
    }
    if (input) {
      input.focus();
      // Also attempt to open flatpickr instance if available
      const fp = input._flatpickr;
      if (fp && typeof fp.open === 'function') fp.open();
    }
  });

  // optional: call init once more after a slight delay in case server-rendered content arrives late
  setTimeout(() => {
    // Run again after a short delay to catch late-rendered content; tag and init any date-like inputs
    setTimeout(() => {
      document.querySelectorAll('input[type="text"]').forEach(function(inp){
        try {
          var n = (inp.name||'').toLowerCase();
          var id = (inp.id||'').toLowerCase();
          var ph = (inp.placeholder||'').toLowerCase();
          if ((n && n.indexOf('date') !== -1) || (id && id.indexOf('date') !== -1) || (ph && ph.indexOf('date') !== -1)) {
            if (!inp.classList.contains('datepicker')) inp.classList.add('datepicker');
            if (!inp.hasAttribute('data-fp-init')) inp.setAttribute('data-fp-init','false');
          }
        } catch (e) {}
      });
      document.querySelectorAll('.datepicker').forEach(initFlatpickrOn);
    }, 300);
  }, 300);
});
</script>

<!-- 🖼️ Image modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">
    <button id="image-modal-close" class="absolute top-4 right-4 text-white text-2xl">&times;</button>
    <div class="max-w-[90vw] max-h-[90vh] p-4">
        <img id="image-modal-img" src="" alt="" class="w-auto h-auto max-w-full max-h-[90vh] object-contain rounded" />
    </div>
</div>

</body>
</html>
