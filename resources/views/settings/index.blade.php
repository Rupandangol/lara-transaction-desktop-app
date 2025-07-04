@extends('layout.app')

@section('content-header')
    Account Settings
@endsection

@section('content')
    {{-- User Details & Password Update --}}
    <div class="flex justify-center px-4 sm:px-6 lg:px-0">
        <div class="bg-white border border-gray-300 rounded-xl p-5 sm:p-6 w-full max-w-md sm:max-w-xl shadow-md">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-4">Update Account</h1>
            <form method="POST" action="{{ route('user.update') }}">
                @csrf
                @method('PATCH')

                {{-- Email (disabled) --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="text" id="email" value="{{ $user->email }}"
                        class="mt-1 block w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed"
                        disabled>
                </div>

                {{-- Current Password --}}
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    @error('current_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New Password --}}
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="new_password" id="new_password"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    @error('new_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="mt-6">
                    <button type="submit"
                        class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2 px-4 rounded-md shadow-md transition duration-200">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Export All Transactions Section --}}
    <div class="flex justify-center mt-10 px-4 sm:px-6 lg:px-0">
        <div class="bg-white border border-gray-300 rounded-2xl p-6 sm:p-8 w-full max-w-md sm:max-w-xl shadow-lg">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3">Download Transaction History</h2>
            <p class="text-gray-700 text-sm sm:text-base leading-relaxed mb-5">
                You can export a complete record of your past transactions. This download includes all available data for
                your account.
            </p>
            <p class="text-gray-600 text-xs sm:text-sm mb-6">
                Note: The file will be generated in CSV format and may take a few seconds depending on your transaction
                volume.
            </p>
            <a href="{{ route('transaction.export') }}"
                class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-md shadow transition duration-200">
                Download All Transactions
            </a>
        </div>
    </div>


    {{-- Purge Transaction Section --}}
    <div class="flex justify-center mt-10 px-4 sm:px-6 lg:px-0">
        <div class="bg-white border border-gray-300 rounded-xl p-5 sm:p-6 w-full max-w-md sm:max-w-xl shadow-md">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2">Purge Transactions</h2>
            <p class="text-gray-700 mb-4 text-sm sm:text-base leading-relaxed">
                This will permanently delete <strong>all your transaction history</strong>. This action cannot be undone.
                Please proceed with caution.
            </p>
            <a href="{{ route('transaction.purge') }}"
                class="block w-full text-center bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md shadow-md transition duration-200">
                Delete All Transactions
            </a>
        </div>
    </div>
@endsection
