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

    <div>
        <div class=" bg-white p-5 shadow-md rounded-md mb-5">
            <div class="flex justify-between">
                <form id="importForm" method="POST" action="{{ route('transaction.import') }}" enctype="multipart/form-data"
                    class="mb-6 ">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="transaction_file">Excel Upload</label>
                        <input class="mt-1 block  border border-gray-300 rounded px-3 py-2 mb-2" type="file"
                            name="transaction_file">
                        @if ($errors->has('transaction_file'))
                            <code class="text-red-800">{{ $errors->first('transaction_file') }}</code>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="source_type">Source_type</label>
                        <select class="mt-1 block  border border-gray-300 rounded px-3 py-2 mb-2" name="source_type"
                            id="source_type">
                            <option value="default">Default</option>
                            <option value="esewa">Esewa</option>
                        </select>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">
                        Upload an Excel file with the following columns:
                        <span
                            class="font-medium text-gray-800">sn(Optional),date_time,description,debit,credit,status,balance(Optional),channel</span>.
                        You can download a <a href="{{ route('transaction.sample') }}"
                            class="text-blue-600 hover:underline">sample
                            file</a>.
                    </p>
                    <button type="submit" id="submitBtn"
                        class="bg-blue-600 text-white px-4 py-2 mt-2 rounded hover:bg-blue-700">
                        Import
                    </button>

                </form>
                <div>
                    <a href="{{ route('transaction.create') }}"
                        class="bg-green-600 px-4 py-2 mt-2 text-sm mr-5 text-white  rounded-md md:block md:w-fit hover:bg-green-800">
                        Add Transaction</a>
                </div>
            </div>

            <form action="{{ route('transaction.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                @csrf
                <div>
                    <label for="year_month" class="block text-sm font-medium text-gray-700">Month</label>
                    <input id="year_month" name="year_month" type="month" value="{{ request('year_month') ?? '' }}"
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                    <select name="year" id="year" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                        @for ($year = now()->year; $year >= 2000; $year--)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
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
        </div>
        <div class="bg-white p-5 shadow-md rounded">

            <div class="mb-5 flex flex-col md:flex-row justify-between gap-4 text-center">
                {{-- Transactions Block --}}
                <div
                    class="bg-white border-l-4 border-blue-400 shadow-md rounded-lg p-4 w-full md:w-1/2 transition hover:scale-[1.01] hover:shadow-lg">
                    <h4 class="text-xl font-semibold text-blue-700 mb-2">📂 Transactions Summary</h4>
                    <div class="text-lg space-y-1">
                        <p>Total Transactions: <strong>{{ $total_transaction }}</strong></p>
                        <p>Total Income: <strong>Rs.{{ $total_income }}</strong></p>
                        <p>Total Spent: <strong>Rs.{{ $total_spent }}</strong></p>
                    </div>
                </div>
                {{-- Forecast Block --}}
                <div
                    class="bg-white border-l-4 border-green-700 shadow-lg rounded-lg p-4 w-full md:w-1/2  transition hover:scale-[1.02] hover:shadow-xl">
                    <h4 class="text-xl font-semibold text-green-800 mb-2">📊 Forecast Summary</h4>
                    <div class="text-lg space-y-1 ">
                        <p>Over all forecast: <strong>Rs.{{ $over_all_forecast }}</strong></p>
                        {{-- <p>Three month forecast: <strong>Rs.{{ $three_month_forecast }}</strong></p> --}}
                        @if ($percent_change != 0)
                            <p class="flex justify-center">Percentage Change:
                                @if ($percent_change['type'] === 'increase')
                                    <span title="Compared to last month, the expenses has increased"
                                        class="text-white bg-red-600 rounded-lg px-1 ml-2 font-bold">
                                        ↑
                                    </span>
                                @else
                                    <span title="Compared to last month, the expenses has decreased"
                                        class="text-white bg-green-600 rounded-lg px-1 ml-2 font-bold">
                                        ↓
                                    </span>
                                @endif
                                <strong class="">{{ $percent_change['change'] }} % </strong>

                            </p>
                        @endif
                        <p class="animate-pulse">Linear Regression forecast (next month):
                            <strong>Rs.{{ $linear_regression_forecast }}</strong>
                        </p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm text-gray-800">
                    <thead class="bg-gray-200 text-left font-semibold">
                        <tr>
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Date & Time</th>
                            <th class="border px-4 py-2">Description</th>
                            <th class="border px-4 py-2">Debit (Rs.)</th>
                            <th class="border px-4 py-2">Credit (Rs.)</th>
                            <th class="border px-4 py-2">Tag</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Channel</th>
                            <th class="border px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $item)
                            <tr class="hover:bg-gray-100 transition">
                                <td class="border px-4 py-2">{{ $item->id }}</td>
                                <td class="border px-4 py-2">{{ $item->date_time }}</td>
                                <td class="border px-4 py-2">{{ $item->description }}</td>
                                <td class="border px-4 py-2">{{ $item->debit }}</td>
                                <td class="border px-4 py-2">{{ $item->credit }}</td>
                                <td class="border px-4 py-2">{{ $item->tag ?? '-' }}</td>
                                <td class="border px-4 py-2">{{ $item->status }}</td>
                                <td class="border px-4 py-2">{{ $item->channel }}</td>
                                <td class="border px-4 py-2 text-center">
                                    <div class="inline-flex gap-2">
                                        <a href="{{ route('transaction.edit', $item->id) }}"
                                            class="bg-gray-700 hover:bg-gray-800 text-white px-3 py-1 rounded text-xs">
                                            Edit
                                        </a>
                                        <form method="post" action="{{ route('transaction.delete', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-4">No transaction data available.
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
        @if ($total_transaction != 0)
            <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-6 mt-8">

                {{-- Line Chart - Monthly Expenses --}}
                <div class="bg-white p-6 rounded-2xl shadow-md w-full flex flex-col items-center text-center">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 justify-center">
                        📈 <span>Monthly Expenses</span>
                    </h2>
                    <div class="h-64 w-full">
                        <canvas id="lineChart" class="mx-auto h-full"></canvas>
                    </div>
                </div>

                {{-- Bar Chart - Hourly Spendings --}}
                <div class="bg-white p-6 rounded-2xl shadow-md w-full flex flex-col items-center text-center">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 justify-center">
                        🕒 <span>Hourly Spendings</span>
                    </h2>
                    <div class="h-64 w-full">
                        <canvas id="barChart" class="mx-auto h-full"></canvas>
                    </div>
                </div>

                {{-- Doughnut Chart - Top Expenses --}}
                <div class="bg-white p-6 rounded-2xl shadow-md w-full flex flex-col items-center text-center">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 justify-center">
                        💸 <span>Top Expenses</span>
                    </h2>
                    <div class="h-72 w-full">
                        <canvas id="doughnutChart" class="mx-auto h-full"></canvas>
                    </div>
                </div>

                {{-- Bar Chart - Tag-based Spendings --}}
                <div class="bg-white p-6 rounded-2xl shadow-md w-full flex flex-col items-center text-center">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 justify-center">
                        🏷️ <span>Tag-based Spendings</span>
                    </h2>
                    <div class="h-64 w-full">
                        <canvas id="barChart2" class="mx-auto h-full"></canvas>
                    </div>
                </div>

                {{-- Horizontal Bar Chart - percent changes --}}
                @if ($percent_changes->isNotEmpty())
                    <div class="bg-white p-6 rounded-2xl shadow-md w-full flex flex-col items-center text-center">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2 justify-center">
                            % <span>Percent changes</span>
                        </h2>
                        <div class="h-64 w-full">
                            <canvas id="horizontalBarChart" class="mx-auto h-full"></canvas>
                        </div>
                    </div>
                @endif
            </div>
        @endif
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
                        label: 'Hour Based Spendings(Count)',
                        data: data,
                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 1,
                        borderRadius: 6
                    },

                    {
                        label: 'Spent(Rs.)',
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
        const doughnutData = @json($top_expenses->map(fn($item) => $item->debit_sum));
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
                    label: 'Spent (Rs.)',
                    data: doughnutData,
                    backgroundColor: doughnutColors,
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

        const lineLabel = @json($monthly_expenses->map(fn($item) => \Carbon\Carbon::createFromFormat('m', $item->month)->format('M') ?? []));
        const lineData = @json($monthly_expenses->map(fn($item) => $item->total_spent ?? []));
        const linectx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(linectx, {
            type: 'line',
            data: {
                labels: lineLabel,
                datasets: [{
                    label: 'Monthly Expenses',
                    data: lineData,
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 4,
                    tension: 0.1,
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5,
                            callback: function(value) {
                                return 'Rs. ' + value;
                            }
                        }
                    }
                }
            }
        });

        const barlabels2 = @json($tag_related_spendings->map(fn($item) => $item->tag));
        const bardata2 = @json($tag_related_spendings->map(fn($item) => $item->total));
        const barsums2 = @json($tag_related_spendings->map(fn($item) => $item->debit_sum));
        const barctx2 = document.getElementById('barChart2').getContext('2d');

        const barChart2 = new Chart(barctx2, {
            type: 'bar',
            data: {
                labels: barlabels2,
                datasets: [{
                        label: 'Tag related count',
                        data: bardata2,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                        ],
                        borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                        ],
                        borderWidth: 1,
                        borderRadius: 6
                    },
                    {
                        label: 'Tag related Spendings',
                        data: barsums2,
                        backgroundColor: [
                            'rgba(201, 203, 207, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                        ],
                        borderColor: [
                            'rgb(201, 203, 207)',
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',

                        ],
                        borderWidth: 1,
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
        //Horizontal bar chart
        const horizontalLabels = @json($percent_changes->map(fn($item) => \Carbon\Carbon::createFromFormat('m', $item['month'])->format('M')));
        const horizontalData = @json($percent_changes->map(fn($item) => $item['changeAbs']));
        const horizontalCtx = document.getElementById('horizontalBarChart').getContext('2d');

        const horizontalBarChart = new Chart(horizontalCtx, {
            type: 'bar',
            data: {
                labels: horizontalLabels,
                datasets: [{
                    label: 'Change percent(%)',
                    data: horizontalData,
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                elements: {
                    bar: {
                        borderWidth: 2,
                    }
                },
            }
        });
    </script>
@endsection
