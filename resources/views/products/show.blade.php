@extends('products.layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-6 min-h-screen">
  <div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700">

      {{-- Header --}}
      <div class="flex justify-between items-center p-5 border-b border-gray-200 dark:border-gray-700">
        <div>
          <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Product Details</h2>
          <p class="text-sm text-gray-500 dark:text-gray-400">Full information for <strong>{{ $product->name }}</strong></p>
        </div>
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center text-sm font-medium text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 px-3 py-1.5 rounded-lg dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition">
          <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Back to Products
        </a>
      </div>

      {{-- Content --}}
      <div class="p-6">
        @if ($product)
          @if ($product->image)
            {{-- Two-column layout when image exists --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              {{-- Image --}}
              <div class="flex justify-center items-start">
                <img 
                  src="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}" 
                  alt="{{ $product->name }}" 
                  class="w-full max-w-sm rounded-xl shadow-md cursor-pointer js-image-modal"
                  data-src="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}">
              </div>

              {{-- Details --}}
              <div class="space-y-4 text-gray-700 dark:text-gray-300">
                <div>
                  <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>
                </div>
                <div>
                  <span class="font-medium text-gray-600 dark:text-gray-400">Details:</span>
                  <p class="mt-1 leading-relaxed">{{ $product->detail }}</p>
                </div>
                <div>
                  <span class="font-medium text-gray-600 dark:text-gray-400">Type:</span>
                  <p class="mt-1">{{ $product->type }}</p>
                </div>
                <div>
                  <span class="font-medium text-gray-600 dark:text-gray-400">Last Updated:</span>
                  <p class="mt-1">{{ $product->updated_at ? $product->updated_at->format('d M Y, h:i A') : '-' }}</p>
                </div>
              </div>
            </div>
          @else
            {{-- Single-column layout when no image --}}
            <div class="text-gray-700 dark:text-gray-300 space-y-4">
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $product->name }}</h3>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Details:</span>
                <p class="mt-1 leading-relaxed">{{ $product->detail }}</p>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Type:</span>
                <p class="mt-1">{{ $product->type }}</p>
              </div>
              <div>
                <span class="font-medium text-gray-600 dark:text-gray-400">Last Updated:</span>
                <p class="mt-1">{{ $product->updated_at ? $product->updated_at->format('d M Y, h:i A') : '-' }}</p>
              </div>
            </div>
          @endif
        @else
          <p class="text-center text-gray-400">No product found.</p>
        @endif
      </div>

    </div>
  </div>
</div>

@endsection
