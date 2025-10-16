@extends('virtual::layout')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4 capitalize">Virtual {{ $table }}</h1>
    {!! $html !!}
</div>
@endsection
