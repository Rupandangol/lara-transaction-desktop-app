@extends('layout.app')
@section('content-header')
    Temp Transaction
@endsection
@section('content')
    <h1 class="text-2xl font-bold mb-2 text-center">Temp</h1>
    <div class="space-y-5">
        <table class="w-full">
            <thead>
                <tr class="text-left font-semibold">
                    <th class="p-2 border">Id</th>
                    <th class="p-2 border">Date</th>
                    <th class="p-2 border">Description</th>
                    <th class="p-2 border">amount</th>
                    <th class="p-2 border">tag</th>
                    <th class="p-2 border">status</th>
                    <th class="p-2 border">channel</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $key=>$item)
                    <tr>
                        <td class="p-2 border">{{ ++$key }}</td>
                        <td class="p-2 border">{{ $item->date_time }}</td>
                        <td class="p-2 border">{{ $item->description }}</td>
                        <td class="p-2 border">{{ $item->debit }}</td>
                        <td class="p-2 border">{{ $item->tag }}</td>
                        <td class="p-2 border">{{ $item->status }}</td>
                        <td class="p-2 border">{{ $item->channel }}</td>
                        <td class="p-2 border">
                            <div>
                                <form method="post" action="{{ route('temp.transaction.destroy', $item->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 bg-red-500 hover:bg-red-600 rounded-md text-xs text-white">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td class="p-2 border" colspan="8">No Data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (count($data) > 0)
            <form id="approve-temp-transaction-form" method="GET" action="{{ route('temp.transaction.approve') }}">
                <button id="approve-temp-transaction"
                    class="p-2 bg-green-500 hover:bg-green-600 rounded-md text-white flex items-center" type="submit">
                    <div id="loader"
                        class="hidden w-5 h-5 border-2 border-white border-t-transparent  rounded-full animate-spin mr-2">
                    </div>
                    <span id="btn-text">
                        Approve
                    </span>
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content-footer')
    <script>
        document.getElementById('approve-temp-transaction-form').addEventListener('submit', function() {
            const btn = document.getElementById('approve-temp-transaction');
            const loader = document.getElementById('loader');
            const btnText=document.getElementById('btn-text');
            btn.disabled = true;
            loader.classList.remove('hidden');
            btn.classList.remove('bg-green-500');
            btn.classList.remove('hover:bg-green-600');
            btn.classList.add('bg-gray-500');
            btnText.innerText = "Processing...";
        });
    </script>
@endsection
