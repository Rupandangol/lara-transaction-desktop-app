@extends('layout.app')

@section('content-header')
    Transaction
@endsection

@section('content')
    <div class="flex justify-center p-5">
        <div class="bg-white shadow-lg rounded-md p-8 w-full max-w-lg">
            <h1 class="text-lg font-medium text-gray-700">Create Transaction</h1>
            <hr class="mb-4 mt-1">
            <form action="{{ route('transaction.store') }}" method="POST">
                @csrf
                {{-- Date Time --}}
                <div class="mb-4">
                    <label for="date_time" class="block text-sm font-medium text-gray-700">Date Time</label>
                    <input value="{{ old('date_time') ?? '' }}" type="datetime-local" max="{{ now() }}"
                        name="date_time" id="date_time"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                    @if ($errors->has('date_time'))
                        <code class="text-red-600">{{ $errors->first('date_time') }}</code>
                    @endif
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input type="text" value="{{ old('description') ?? '' }}" name="description" id="description"
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
                        <option @if (old('transaction_type') == 'debit') selected @endif value="debit">Debit</option>
                        <option @if (old('transaction_type') == 'credit') selected @endif value="credit">Credit</option>
                    </select>
                </div>
                {{-- Amount --}}
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                    <input type="number" value="{{ old('amount') ?? '' }}" name="amount" id="amount"
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
                        @foreach ($status_list as $item)
                            <option @if (old('status') == $item->value) selected @endif value={{ $item->value }}>
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Channel --}}
                <div class="mb-4">
                    <label for="channel" class="block text-sm font-medium text-gray-700">Channel</label>
                    <select name="channel" id="channel"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                        @foreach ($channel_list as $item)
                            <option @if (old('channel') == $item->value) selected @endif value="{{ $item->value }}">
                                {{ $item->name }}</option>
                        @endforeach

                    </select>
                </div>

                {{-- Tag --}}
                <div class="mb-4">
                    <label for="tag" class="block text-sm font-medium text-gray-700">Tag</label>
                    <select name="tag" id="tag"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-green-200 focus:border-green-500 p-2">
                        @foreach ($tag_list as $item)
                            <option @if (old('tag') == $item->value) selected @endif value="{{$item->value}}">
                                {{str_replace('_',' ',$item->name)}}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                    Submit
                </button>

            </form>
            <a class="block mt-4 w-full text-center bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-md"
                href="{{ route('transaction.index') }}">Back</a>
        </div>
    </div>
@endsection
