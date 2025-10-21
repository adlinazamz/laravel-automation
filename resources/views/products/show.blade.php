@extends('products.layout')
@section('content')

<div class="max-w-3xl mx-auto mt-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="text-2xl font-semibold text-gray-800">Show Product</h2>
        </div>

        <div class="p-6 space-y-4">
            <div class="flex justify-end">
                <a href="{{ route('products.index') }}" class="inline-flex items-center px-3 py-2 rounded-md bg-gray-100 hover:bg-gray-200 text-sm text-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                    Back to Products
                </a>
            </div>

            @if ($product)
            <div>
                <div class="mb-2"><strong>Name:</strong> {{ $product->name }}</div>
                <div class="mb-2"><strong>Details:</strong> {{ $product->detail }}</div>
                <div class="mb-2"><strong>Type:</strong> {{ $product->type }}</div>
                <div class="mb-2"><strong>Updated at:</strong> {{ $product->updated_at ? $product->updated_at->format('d M Y') : '-' }}</div>
            </div>

            <div>
                <strong>Image:</strong>
                <div class="mt-2">
                    <img src="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}" alt="{{ $product->name }}" class="cursor-pointer js-image-modal max-w-full h-auto rounded shadow" data-src="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}">
                </div>
            </div>

            @else
            <p>No product found.</p>
            @endif

        </div>
    </div>
</div>
@endsection