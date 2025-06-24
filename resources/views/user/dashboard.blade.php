@extends('layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Welcome!</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-4 bg-white rounded shadow">Card 1</div>
        <div class="p-4 bg-white rounded shadow">Card 2</div>
        <div class="p-4 bg-white rounded shadow">Card 3</div>
        <a href="{{route('login')}}" class="bg-white rounded shadow-md p-2">Login</a>
    </div>
@endsection
