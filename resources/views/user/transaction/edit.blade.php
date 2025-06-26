@extends('layout.app')

@section('content-header')
    Transaction
@endsection

@section('content')
    <div class="flex justify-center p-5">
        <div class="bg-white shadow-lg rounded-md p-8 w-full max-w-lg">
            <h1 class="text-lg font-medium text-gray-700">Update Transaction</h1>
            <hr class="mb-4 mt-1">
            <form action="{{ route('transaction.update', $transaction['id']) }}" method="POST">
                @csrf
                @method('PATCH')
                {{-- Date Time --}}
                <div class="mb-4">
                    <label for="date_time" class="block text-sm font-medium text-gray-700">Date Time</label>
                    <input value="{{ $transaction['date_time'] ?? (old('date_time') ?? '') }}" type="datetime-local"
                        name="date_time" id="date_time"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                    @if ($errors->has('date_time'))
                        <code class="text-red-600">{{ $errors->first('date_time') }}</code>
                    @endif
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" value="{{ $transaction['description'] ?? (old('description') ?? '') }}"
                        name="description" id="description"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                    @if ($errors->has('description'))
                        <code class="text-red-600">{{ $errors->first('description') }}</code>
                    @endif
                </div>

                {{-- Transaction type --}}
                <div class="mb-4">
                    <label for="transaction_type" class="block text-sm font-medium text-gray-700">Transaction Type</label>
                    <select name="transaction_type" id="transaction_type"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                        <option @if ($transaction['transaction_type'] == 'debit') selected @endif value="debit">Debit</option>
                        <option @if ($transaction['transaction_type'] == 'credit') selected @endif value="credit">Credit</option>
                    </select>
                </div>
                {{-- Amount --}}
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number"value="{{ $transaction['amount'] ?? (old('amount') ?? '') }}" name="amount"
                        id="amount"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                    @if ($errors->has('amount'))
                        <code class="text-red-600">{{ $errors->first('amount') }}</code>
                    @endif
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                        <option @if ($transaction['status'] == 'COMPLETE') selected @endif value="COMPLETE">Complete</option>
                        <option @if ($transaction['status'] == 'PENDING') selected @endif value="PENDING">Pending</option>
                        <option @if ($transaction['status'] == 'INCOMPLETE') selected @endif value="INCOMPLETE">Incomplete</option>
                    </select>
                </div>

                {{-- Channel --}}
                <div class="mb-4">
                    <label for="channel" class="block text-sm font-medium text-gray-700">Channel</label>
                    <select name="channel" id="channel"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                        <option @if ($transaction['channel'] == 'App') selected @endif value="App">App</option>
                        <option @if ($transaction['channel'] == 'THIRDPARTY') selected @endif value="THIRDPARTY">Third Party</option>
                        <option @if ($transaction['channel'] == 'FONEPAY') selected @endif value="FONEPAY">FonePay</option>
                        <option @if ($transaction['channel'] == 'STRIPE') selected @endif value="STRIPE">Stripe</option>
                        <option @if ($transaction['channel'] == 'PAYPAL') selected @endif value="PAYPAL">PayPal</option>
                        <option @if ($transaction['channel'] == 'WECHAT') selected @endif value="WECHAT">WeChat</option>
                        <option @if ($transaction['channel'] == 'CASH') selected @endif value="CASH">Cash</option>
                        <option @if ($transaction['channel'] == 'OTHERS') selected @endif value="OTHERS">Others</option>
                    </select>
                </div>

                {{-- Tag --}}
                <div class="mb-4">
                    <label for="tag" class="block text-sm font-medium text-gray-700">Tag</label>
                    <select name="tag" id="tag"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                        <option @if ($transaction['tag'] == 'bill_sharing') selected @endif value="bill_sharing">Bill Sharing
                        </option>
                        <option @if ($transaction['tag'] == 'family_expenses') selected @endif value="family_expenses">Family Expenses
                        </option>
                        <option @if ($transaction['tag'] == 'groceries') selected @endif value="groceries">Groceries</option>
                        <option @if ($transaction['tag'] == 'lend') selected @endif value="lend">Lend/Borrow</option>
                        <option @if ($transaction['tag'] == 'personal_use') selected @endif value="personal_use">Personal Use
                        </option>
                        <option @if ($transaction['tag'] == 'ride_sharing') selected @endif value="ride_sharing">Ride Sharing
                        </option>
                        <option @if ($transaction['tag'] == 'others') selected @endif value="others">Others</option>
                    </select>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                    Submit
                </button>

            </form>
            <a class="block text-center mt-4 w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md"
                href="{{ route('transaction.index') }}">Back</a>
        </div>
    </div>
@endsection
