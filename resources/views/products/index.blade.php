@extends('products.layout')
@section('content')

<div class="ml-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Import/Export Section -->
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-6">
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
            @csrf
            <label class="inline-flex items-center bg-gray-700 hover:bg-gray-600 text-gray-300 px-3 py-2 rounded cursor-pointer">
                <input type="file" name="file" class="hidden file-input">
                <i class="fa fa-file mr-2"></i> Upload
            </label>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm import-button">
                <i class="fa fa-file-import mr-1"></i> Import
            </button>
            <a href="{{ route('products.export') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm export-button">
                <i class="fa fa-download mr-1"></i> Export
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
        <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm flex items-center gap-2 create-button">
            <i class="fa fa-plus"></i> New Product
        </a>
    </div>

    <!-- Product Table -->
    <div class="overflow-hidden rounded-lg bg-gray-800">
        <table class="w-full text-sm text-left text-gray-300">
            <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Image</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Details</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Updated At</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse ($products as $product)
                    <tr class="hover:bg-gray-700">
                        <td class="px-6 py-4">{{ ++$i }}</td>
                        <td class="px-6 py-4">
                            <img src="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}" class="w-10 h-auto rounded cursor-pointer open-modal" data-img="{{ Str::startsWith($product->image, '/storage/') ? $product->image : '/images/' . $product->image }}"  />
                        </td>
                        <td class="px-6 py-4">{{ $product->name }}</td>
                        <td class="px-6 py-4 truncate max-w-xs">{{ $product->detail }}</td>
                        <td class="px-6 py-4">{{ $product->type }}</td>
                        <td class="px-6 py-4">{{ $product->updated_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('products.show', $product->id) }}" class="text-blue-400 hover:text-blue-300 mr-2 show-button">Show</a>
                            <a href="{{ route('products.edit', $product->id) }}" class="text-blue-400 hover:text-blue-300 mr-2 edit-button">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-400 delete-button" data-name="{{ $product->name }}">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-400">No products found.</td>
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