@extends('products.layout')

@section('content')

<div class="card mt-5">
  <h2 class="card-header">Edit Product</h2>
  <div class="card-body">

    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        <a class="btn btn-primary btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
        <a class="btn btn-secondary btn-sm" href="{{ route('products.index') }}"><i class="fa fa-arrow-left"></i> Back to Products</a>
    </div>

    <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

      

        <div class="mb-3">
            <label for="inputImage" class="form-label"><strong>Image</strong></label>
            <input
                type="file"
                name="image"
                class="form-control @error('image') is-invalid @enderror"
                id="inputImage">
            <img src = '{{ $product->image }}' width  ="300px">
            @error('image')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="inputName" class="form-label"><strong>Name:</strong></label>
            <input
                type="text"
                name="name"
                value="{{ $product->name }}"
                class="form-control @error('name') is-invalid @enderror"
                id="inputName"
                placeholder="Name">
            @error('name')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="inputDetails" class="form-label"><strong>Details:</strong></label>
            <textarea
                class="form-control @error('details') is-invalid @enderror"
                style="height:150px"
                name="details"
                id="inputDetails"
                placeholder="Details">{{ $product->details }}</textarea>
            @error('details')
                <div class="form-text text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Update</button>
    </form>

  </div>
</div>
@endsection