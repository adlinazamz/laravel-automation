@extends('products.layout')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Import/Export Section -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-6">
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <input type="file" name="file" class="block w-48 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs import-button">
                <i class="fa fa-file"></i> Import
            </button>
               <a href="{{ route('products.export') }}" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs export-button">
            <i class="fa fa-download"></i> Export
        </a>
         </form>
    </div>

    <!-- Date Filter Form -->
    <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap gap-4 mb-6">
        <div>
            <label for="date_from" class="block text-sm font-medium text-gray-700">On</label>
            <input type="text" id="date_from" name="date_from" class="datepicker mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ request('date_from') }}" placeholder="dd-mm-yyyy">
        </div>
        <div>
            <label for="date_to" class="block text-sm font-medium text-gray-700">To (optional)</label>
            <input type="text" id="date_to" name="date_to" class="datepicker mt-1 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ request('date_to') }}" placeholder="dd-mm-yyyy">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs filter-button" >Filter</button>
            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs reset-button" >Reset</a>
        </div>
    </form>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-end gap-2 mb-4">
        <a href="{{ route('products.create') }}"class= "bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-2 create-button">
            <i class="fa fa-plus"></i> New Product
        </a>
    </div>

    <!-- Product Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-600 border border-gray-800 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">No</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Image</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Name</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Details</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Type</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Updated At</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($products as $product)
                    <tr>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ ++$i }}</td>
                        <td class="border px-4 py-2">
                            <img src="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}" class="w-8 h-auto rounded cursor-pointer open-modal" data-img="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}"  />
                        </td>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ $product->name }}</td>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ $product->detail }}</td>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ $product->type }}</td>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ $product->updated_at->format('d M Y') }}</td>
                        <td class="border px-4 py-2 flex gap-2 flex-wrap">
                            <a href="{{ route('products.show', $product->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs show-button" >
                                <i class="fa-solid fa-list"></i> Show
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs edit-button" >
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs delete-button" data-name="{{ $product->name }}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {!! $products->links() !!}
    </div>
</div>

<!-- Tailwind Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white p-4 rounded shadow-lg relative">
        <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700" onclick="document.getElementById('imageModal').classList.add('hidden')">✕</button>
        <img id="modalImage" src="" class="max-w-full h-auto mt-4" alt="Product Image">
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const name = form.querySelector('.delete-button').dataset.name || 'this product';
                swal({
                    title: `Delete ${name}?`,
                    text: "This action cannot be undone.",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) form.submit();
                });
            });
        });

        document.querySelectorAll('.open-modal').forEach(img => {
            img.addEventListener('click', function () {
                const modal = document.getElementById('imageModal');
                const modalImg = document.getElementById('modalImage');
                modalImg.src = this.getAttribute('data-img');
                modal.classList.remove('hidden');
            });
        });
    });
</script>
@endsection