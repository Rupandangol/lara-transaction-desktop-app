@extends('auth-layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#101828] to-[#1f2937]">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-[#101828] mb-6">Create your account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                    class="text-black mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#101828] focus:ring-[#101828]"
                    required autofocus>
                @error('name')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                    class="text-black mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#101828] focus:ring-[#101828]"
                    required>
                @error('email')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password"
                    class="text-black mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#101828] focus:ring-[#101828]"
                    required>
                @error('password')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password-confirm" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation"
                    class="text-black mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-[#101828] focus:ring-[#101828]"
                    required>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="w-full bg-[#101828] text-white py-2 px-4 rounded-md hover:bg-[#1f2c3e] transition duration-150">
                    Register
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#101828] hover:underline">Login here</a>
        </p>
    </div>
</div>
@endsection
