@extends('products.layout')
@section('content')

<div class="max-w-2xl mx-auto mt-10 bg-white shadow-md rounded-lg">
  <h2 class="text-xl font-semibold bg-gray-100 px-6 py-4 rounded-t-lg">Show Product</h2>
  <div class="px-6 py-4">

    <div class="flex justify-end mb-4">
      <a href="{{ route('products.index') }}" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-2 rounded inline-flex items-center">
        <i class="fa fa-arrow-left mr-1"></i> Back to Products
      </a>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
        @if ($product)   
        <div class="form-group">
                <strong>Name:</strong>
                {{ $product->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Details:</strong>
                {{ $product->detail }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Updated at:</strong> 
                {{ $product->updated_at ? $product->updated_at->format('d M Y') : '-' }}           
            </div>
        </div>
    </div>
    @else
        <p>No product found.</p>
    @endif
    
    <div class="col-xs-12 col-sm-12 col-md-12 mt-2">
            <div class="form-group">
                <strong>Image:</strong>
                <img src = "{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}" width ="500px">
            </div>
        </div>
    
    </div>
</div>
@endsection