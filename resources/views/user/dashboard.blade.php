@extends('layout.app')

@section('content-header')
    Home
@endsection

@section('content')
    <h1 class="text-2xl font-bold mb-4">Welcome to Spendlite App!</h1>

    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-6" role="alert">
        <p class="font-semibold">Ongoing Project</p>
        <p>This project is still in active development. If you find any bugs or have suggestions, please feel free to raise
            an issue on GitHub.</p>
        <a href="https://github.com/Rupandangol/lara-transaction-desktop-app/issues" target="_blank"
            class="inline-block mt-2 text-blue-600 hover:underline">
            👉 Submit an issue on GitHub
        </a>
    </div>
@endsection
