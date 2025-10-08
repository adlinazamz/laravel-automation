@extends('tester.layout')
@section('content')

@if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-end gap-2 mb-4">
        <a href="{{ route('tester.create') }}"class= "bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-2 create-button">
            <i class="fa fa-plus"></i> New Testers
        </a>
    </div>

    <!-- Testers Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-600 border border-gray-800 rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">No</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Name</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Created At</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Updated At</th>
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($tester as $tester)
                    <tr>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ ++$i }}</td>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ $tester->name }}</td>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ $tester->created_at->format('d M Y') }}</td>
                        <td class="border px-4 py-2 text-sm text-gray-800">{{ $tester->updated_at->format('d M Y') }}</td>
                        <td class="border px-4 py-2 flex gap-2 flex-wrap">
                            <a href="{{ route('tester.show', $tester->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs show-button" >
                                <i class="fa-solid fa-list"></i> Show
                            </a>
                            <a href="{{ route('tester.edit', $tester->id) }}" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs edit-button" >
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form action="{{ route('tester.destroy', $tester->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs delete-button" data-name="{{ $tester->name }}">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">No tester found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
                const name = form.querySelector('.delete-button').dataset.name || 'this tester';
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