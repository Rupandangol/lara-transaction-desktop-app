@extends('auth-layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#101828] to-[#1F2937] px-4">
    <div class="w-full max-w-md bg-white text-gray-800 rounded-xl shadow-xl p-8">
        <h2 class="text-2xl font-semibold text-center text-[#101828] mb-6">Welcome Back</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="email" type="email" name="email"
                    value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#101828] focus:border-[#101828] @error('email') border-red @enderror">
                @error('email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-[#101828] focus:border-[#101828] @error('password') border-red @enderror">
                @error('password')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center mb-4">
                <input id="remember" type="checkbox" name="remember"
                    class="h-4 w-4 text-[#101828] focus:ring-[#101828] border-gray-300 rounded"
                    {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="ml-2 block text-sm text-gray-700">
                    Remember Me
                </label>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit"
                    class="w-full bg-[#101828] text-white py-2 px-4 rounded-md hover:bg-[#0f1b24] transition">
                    Login
                </button>
            </div>

            <!-- Forgot Password -->
            {{-- @if (Route::has('password.request'))
                <div class="text-right mt-4">
                    <a class="text-sm text-[#1D4ED8] hover:underline" href="{{ route('password.request') }}">
                        Forgot Your Password?
                    </a>
                </div>
            @endif --}}
        </form>
    </div>
</div>
@endsection
