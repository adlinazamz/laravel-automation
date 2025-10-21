@extends('virtual::layout')
@section('content')

<div class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
    <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
        <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
            {{$modelName}}
            <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Browse a list of {{$modelName}}</p>
        </caption>    
        <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                    <a href="{{ route('virtual.create', ['table' => $modelNameLower]) }}" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" /></svg>
                        New {{ $modelName }}
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <div class="overflow-hidden rounded-lg bg-gray-800">
                    <table class="w-full text-sm text-left text-gray-300">
                        <thead class="text-xs text-gray-400 uppercase bg-gray-700">
                            <tr>
                                {{-- Ensure ID column is first --}}
                                <th scope="col" class="px-6 py-3">ID</th>
                                {{-- Render each field as its own column (exclude id to avoid duplication) --}}
                                @foreach($fields as $f)
                                    @if(strtolower($f) !== 'id')
                                        <th scope="col" class="px-6 py-3">{{ ucfirst($f) }}</th>
                                    @endif
                                @endforeach
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse ($rows as $i => $row)
                                <tr class="bg-transparent hover:bg-gray-700">
                                    <td class="px-6 py-4 font-medium">{{ $row->id ?? ('#' . ($i+1)) }}</td>
                                    @foreach($fields as $f)
                                        @if(strtolower($f) !== 'id')
                                            <td class="px-6 py-4 truncate max-w-xs">{{ $row->$f }}</td>
                                        @endif
                                    @endforeach
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="{{ route('virtual.show', ['table'=>$modelNameLower,'id'=>$row->id]) }}" class="text-blue-400 hover:text-blue-300">Show</a>
                                        <a href="{{ route('virtual.edit', ['table'=>$modelNameLower,'id'=>$row->id]) }}" class="text-blue-400 hover:text-blue-300">Edit</a>
                                        <form action="{{ route('virtual.destroy',['table'=>$modelNameLower,'id'=>$row->id]) }}" method="POST" class="inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-400 delete-button" data-name="{{ $row->name ?? '' }}">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ max(3, count($fields)) + 1 }}" class="px-6 py-6 text-center text-gray-400">No {{ $modelNameLower }} found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">Showing <span class="font-semibold text-gray-900 dark:text-white">1-10</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ $rows->total() ?? 'N/A' }}</span></span>
                @if(method_exists($rows,'links'))
                    <div>{{ $rows->links() }}</div>
                @endif
            </nav>
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
    });
</script>
@endsection