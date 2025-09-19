@extends('products.layout')

@section('content')

<div class="card mt-5" id="product-index-container">
  <h2 class="card-header">Laravel 10 CRUD from scratch - ItSolutionStuff.com</h2>
  <div class="card-body">

    <!-- Import/Export Section -->
    <div class="row mb-4 align-items-end">
        <div class="col-md-6">
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                @csrf
                <input type="file" name="file" class="form-control" style="max-width: 200px;">
                <button class="btn btn-success"><i class="fa fa-file"></i> Import Products</button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a class="btn btn-info" href="{{ route('products.export') }}"><i class="fa fa-download"></i> Export Products</a>
        </div>
    </div>

    <!-- Date Filter Form -->
    <form method="GET" action="{{ route('products.index') }}" class="row g-3 mb-4 align-items-end">
        <div class="col-auto">
            <label for="date_from" class="form-label">On</label>
            <input type="text" id="date_from" name="date_from" class="form-control datepicker" autocomplete="off" value="{{ request('date_from') }}" placeholder="dd-mm-yyyy">
        </div>
        <div class="col-auto">
            <label for="date_to" class="form-label">To (optional)</label>
            <input type="text" id="date_to" name="date_to" class="form-control datepicker" autocomplete="off" value="{{ request('date_to') }}" placeholder="dd-mm-yyyy">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>
    <script>
    $(function() {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
    });
    </script>
        @session('success')
            <div class="alert alert-success" role="alert"> {{ $value }} </div>
        @endsession

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary btn-sm" href="{{ route('dashboard') }}"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
            <a class="btn btn-success btn-sm" href="{{ route('products.create') }}"> <i class="fa fa-plus"></i> Create New Product</a>
        </div>

        <table class="table table-bordered table-striped mt-4">
            <tr>
            <th colspan="6"> List of Products </th>
            </tr>
                <tr>
                    <th width="80px">No</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Details</th>
                    <th>Updated At</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td> <img src="{{$product->image}}" width="100px" class="img-thumbnail open-modal" data-bs-toggle="modal" data-bs-target="#imageModal" data-img="{{$product->image}}"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->detail}}</td>
                    <td>{{$product ->updated_at-> format ('d M Y');}}</td>
                    <td>
                        <form action="{{ route('products.destroy',$product->id) }}" method="POST">

                            <a class="btn btn-info btn-sm" href="{{ route('products.show',$product->id) }}"><i class="fa-solid fa-list"></i> Show</a>

                            <a class="btn btn-primary btn-sm" href="{{ route('products.edit',$product->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">There are no data.</td>
                </tr>
            @endforelse
            </tbody>

        </table>

         {!! $products->links() !!}

  </div>
</div>

<!--modal-->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
              <img id="modalImage" src="" class="img-fluid" alt="Product Image">
          </div>
      </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.open-modal').forEach(function(img) {
        img.addEventListener('click', function() {
            var modalImg = document.getElementById('modalImage');
            modalImg.src = this.getAttribute('data-img');
        });
    });
});
</script>

@endsection