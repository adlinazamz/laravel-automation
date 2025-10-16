@extends('virtual::layout')
@section('content')

<div class="ml-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-end gap-1 mb-4 p-4">
        <a href="{{ route('virtual.create', ['table' => $modelNameLower]) }}" class= "bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-sm flex items-center gap-2 create-button">
            <i class="fa fa-plus"></i> New {{$modelName}}
        </a>
    </div>

    <!-- {{$modelName}} Table -->
    <div class="overflow-x-auto">
        <table class="divide-y divide-gray-600 border border-gray-800 rounded-lg">
           <thead class="bg-gray-100">
                <tr>
        <th class="border px-2 py-2 text-center text-sm font-medium text-gray-700 w-12">No</th>
                    {!! $tableHeaders !!}
                    <th class="border px-4 py-2 text-center text-sm font-medium text-gray-700 whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($rows as $i => $row)
                <tr>
                    <td class="border px-2 py-2 text-sm text-gray-800 text-center w-12">{{ $i + 1 }}</td>
                    @foreach ($fields as $f)
                <td class="border px-4 py-2 text-sm text-gray-800">
                    {{ $row->$f }}
                </td>
            @endforeach

                <td class="border px-2 py-2 text-sm text-gray-800 whitespace-nowrap">
                <div class="flex flex-wrap justify-center gap-2">
                    <a href="{{ route('virtual.show', ['table'=>$modelNameLower,'id'=>$row->id]) }}"                       
                        class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                        <i class="fa-solid fa-list"></i> Show
                    </a>
                    <a href="{{ route('virtual.edit', ['table'=>$modelNameLower,'id'=>$row->id]) }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-2 py-1 rounded text-xs">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <form action="{{ route('virtual.destroy',['table'=>$modelNameLower,'id'=>$row->id]) }}"
                          method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs delete-button"
                                data-name="{{ $row->name ?? '' }}">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="{{ count($fields) + 2 }}" class="px-4 py-4 text-center text-gray-500">
                No {{ $modelNameLower }} found.
            </td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>
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
                const name = form.querySelector('.delete-button').dataset.name || 'this {{$modelNameLower}}';
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