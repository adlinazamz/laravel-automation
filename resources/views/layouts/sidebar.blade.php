<!-- Navigation Links -->
<div class=" w-full flex flex-col gap-10 p-5">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
       {{ __('Dashboard') }}
     </x-nav-link>
    <x-nav-link :href="route('products.index')" :active="request()->routeIs('product')">
         {{ __('Products') }}
      </x-nav-link>
      <x-nav-link :href="route('products.index')" :active="request()->routeIs('product')">
         {{ __('Products') }}
      </x-nav-link>
</div>
  