@extends('layout.app')
@section('content-header')
    Transaction
@endsection
@section('content')
    @if (session('status') === 'success')
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @elseif (session('message'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="container mx-auto px-4">
        <form method="POST" action="{{ route('transaction.import') }}" enctype="multipart/form-data" class="mb-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700" for="transaction_file">Excel or Csv</label>
                <input class="mt-1 block  border border-gray-300 rounded px-3 py-2 mb-2" type="file"
                    name="transaction_file">
                @if ($errors->has('transaction_file'))
                    <code class="text-red-800">{{ $errors->first('transaction_file') }}</code>
                @endif
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Import
            </button>
        </form>

        <form action="{{ route('transaction.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @csrf
            <div>
                <label for="year_month" class="block text-sm font-medium text-gray-700">Month</label>
                <input id="year_month" name="year_month" type="month" value="{{ request('year_month') ?? '' }}"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input id="date" value="{{ request('date') ?? '' }}" name="date" type="date"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <input id="description" name="description" type="text" value="{{ request('description') ?? '' }}"
                    class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
            </div>
            <div class="md:col-span-3 flex gap-4 mt-4">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow transition">
                    Submit
                </button>

                <a href="{{ route('transaction.index') }}"
                    class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400">Clear</a>
            </div>
        </form>

        <hr class="my-4">
        <div class="bg-white p-5 shadow-md rounded">
            <div class="mb-4 flex justify-between text-center">
                <div class="bg-gray-100 rounded shadow-md p-2">
                    <h5>Total Transactions: <strong>{{ $total_transaction }}</strong></h5>
                    <h5>Total Spent: <strong>Rs.{{ $total_spent }}</strong></h5>
                </div>
                <div class="bg-gray-100 rounded shadow-md p-2">
                    <h5>Over all forcast: <strong>Rs.{{ $over_all_forecast }}</strong></h5>
                    <h5>Three month forcast: <strong>Rs.{{ $three_month_forecast }}</strong></h5>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2 text-left">ID</th>
                            <th class="border px-4 py-2 text-left">Date & Time</th>
                            <th class="border px-4 py-2 text-left">Description</th>
                            <th class="border px-4 py-2 text-left">Debit(Rs.)</th>
                            <th class="border px-4 py-2 text-left">Credit(Rs.)</th>
                            <th class="border px-4 py-2 text-left">Tag</th>
                            <th class="border px-4 py-2 text-left">Status</th>
                            <th class="border px-4 py-2 text-left">Channel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-4 py-2">{{ $item->id }}</td>
                                <td class="border px-4 py-2">{{ $item->date_time }}</td>
                                <td class="border px-4 py-2">{{ $item->description }}</td>
                                <td class="border px-4 py-2">{{ $item->debit }}</td>
                                <td class="border px-4 py-2">{{ $item->credit }}</td>
                                <td class="border px-4 py-2">{{ $item->tag }}</td>
                                <td class="border px-4 py-2">{{ $item->status }}</td>
                                <td class="border px-4 py-2">{{ $item->channel }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-500 py-4">No transaction data available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
        <div class="bg-white mt-4 p-6 rounded shadow-md">
            <h2 class="text-lg font-semibold mb-4">Over all hour based spendings</h2>
            <canvas id="barChart" class="w-full h-64"></canvas>
        </div>

        <div class="bg-white mt-4 p-6 rounded shadow-md">
            <h2 class="text-lg font-semibold mb-4">Top expenses</h2>
            <canvas id="doughnutChart" class="w-150 h-64 mx-auto"></canvas>
        </div>
    </div>
@endsection

@section('app-footer')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = @json($over_all_time_based_spendings->map(fn($item) => $item->hour));
        const data = @json($over_all_time_based_spendings->map(fn($item) => $item->total));
        const sums = @json($over_all_time_based_spendings->map(fn($item) => $item->sum));
        const ctx = document.getElementById('barChart').getContext('2d');

        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Hour Based Spendings',
                        data: data,
                        backgroundColor: 'rgba(99, 102, 241, 0.7)', // indigo-500
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    },

                    {
                        label: 'Spent',
                        data: sums,
                        backgroundColor: 'rgba(240, 180, 50, 0.2)', // Optional, or transparent
                        hidden: true // hides from the graph but appears in tooltip
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });

        const doughnutLabel = @json($top_expenses->map(fn($item) => $item->description));
        const doughnutData = @json($top_expenses->map(fn($item) => $item->total));
        const doughnutctx = document.getElementById('doughnutChart').getContext('2d');
        const doughnutColors = [
            '#6366F1', '#F59E0B', '#10B981', '#EF4444', '#3B82F6',
            '#8B5CF6', '#F43F5E', '#22C55E', '#EAB308', '#06B6D4'
        ];
        const doughnutChart = new Chart(doughnutctx, {
            type: 'doughnut',
            data: {
                labels: doughnutLabel,
                datasets: [{
                    label: 'Hour Based Spendings',
                    data: doughnutData,
                    backgroundColor: doughnutColors, // indigo-500
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });
    </script>
@endsection
