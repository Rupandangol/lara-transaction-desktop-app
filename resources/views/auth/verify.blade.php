@extends('auth-layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-[#101828] to-[#1f2937] px-4">
    <div class="w-full max-w-md bg-white shadow-xl rounded-xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Verify Your Email Address</h2>

        @if (session('resent'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif

        <p class="text-gray-600 mb-4">
            {{ __('Before proceeding, please check your email for a verification link.') }}
        </p>

        <p class="text-gray-600 mb-4">
            {{ __('If you did not receive the email') }},
        </p>

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="text-blue-600 hover:underline font-medium">
                {{ __('click here to request another') }}
            </button>
        </form>
    </div>
</div>
@endsection
