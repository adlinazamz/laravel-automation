@extends('products.layout')
@section('content')

<div class="card mt-5">
  <h2 class="card-header">Show Product</h2>
  <div class="card-body">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
        <a class="btn btn-secondary btn-sm" href="{{ route('products.index') }}"><i class="fa fa-arrow-left"></i> Back to Products</a>
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