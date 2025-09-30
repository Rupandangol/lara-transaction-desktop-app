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
        @include('user.transaction.partials.uploadAndFilterblock')

        {{-- Main Content --}}

        @include('user.transaction.partials.transactionTableblock')

        {{-- Charts and Analytics --}}
        @if ($total_transaction != 0)
            @if ($monthly_expenses->isNotEmpty())
                <div class="bg-white px-35 py-6 rounded-2xl shadow-md w-full items-center text-center mb-4">
                    <h1 class="text-2xl font-semibold text-gray-800 mb-4">
                        📈 <span>Monthly Expenses</span>
                    </h1>
                    <div class="">
                        <canvas id="lineChart" class=""></canvas>
                    </div>
                </div>
            @endif
            <div class="bg-white px-35 py-6 rounded-2xl shadow-md w-full  items-center text-center mb-4">
                <h1 class="text-2xl font-semibold text-gray-800 mb-4">
                    🕒 <span>Hourly Spendings</span>
                </h1>
                <div class="">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                <div class="bg-white px-35 py-6 rounded-2xl shadow-md w-full  items-center text-center mb-4">
                    <h1 class="text-2xl font-semibold text-gray-800 mb-4">
                        💸 <span>Top Expenses</span>
                    </h1>
                    <div class="w-full h-96">
                        <canvas id="doughnutChart" class="mx-auto h-full"></canvas>
                    </div>
                </div>

                <div class="bg-white px-6 py-10 rounded-2xl shadow-md w-full  items-center text-center mb-4">
                    <h1 class="text-2xl font-semibold text-gray-800 mb-4">
                        🏷️ <span>Tag-based Spendings</span>
                    </h1>
                    <div class="">
                        <canvas id="barChart2"></canvas>
                    </div>
                </div>
            </div>
            @if ($percent_changes->isNotEmpty())
                <div class="bg-white px-35 py-6 rounded-2xl shadow-md w-full  items-center text-center mb-4">
                    <h1 class=" font-semibold text-2xl text-gray-800 mb-4">
                        % <span>Percent changes</span>
                    </h1>
                    <div class="">
                        <canvas id="horizontalBarChart"></canvas>
                    </div>
                </div>
            @endif
        @endif
    </div>
@endsection

@section('app-footer')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const barChartCanvas = document.getElementById('barChart');
        if (barChartCanvas) {
            const labels = @json($over_all_time_based_spendings->map(fn($item) => $item->hour));
            const data = @json($over_all_time_based_spendings->map(fn($item) => $item->total));
            const sums = @json($over_all_time_based_spendings->map(fn($item) => $item->sum));
            const ctx = barChartCanvas.getContext('2d');

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
                        x: {
                            title: {
                                display: true,
                                text: 'Hours-24'
                            },
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Spendings'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    onClick: function(evt, elements) {
                        console.log(elements);
                        if (elements.length > 0) {
                            const idx = elements[0].index;
                            const selectedHour = labels[idx];
                            const url = new URL(window.location.href);
                            url.searchParams.set('hour', selectedHour);
                            window.location.href = url.toString();
                        }
                    }
                }
            });
        }

        const doughnutCanvas = document.getElementById('doughnutChart');
        if (doughnutCanvas) {
            const doughnutLabel = @json($top_expenses->map(fn($item) => $item->description));
            const doughnutData = @json($top_expenses->map(fn($item) => $item->debit_sum));
            const doughnutctx = doughnutCanvas.getContext('2d');
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
                    onClick: function(evt, elements) {
                        console.log(elements);
                        if (elements.length > 0) {
                            const idx = elements[0].index;
                            const selectedExpenses = doughnutLabel[idx];
                            const url = new URL(window.location.href);
                            url.searchParams.set('description', selectedExpenses);
                            url.searchParams.set('sort_order', 'desc');
                            url.searchParams.set('sort_by', 'debit');
                            window.location.href = url.toString();
                        }
                    }
                }
            });
        }

        const lineChartCanvas = document.getElementById('lineChart');
        if (lineChartCanvas) {
            const lineLabel = @json($monthly_expenses->map(fn($item) => \Carbon\Carbon::createFromFormat('m', $item->month)->format('M') ?? []));
            const lineData = @json($monthly_expenses->map(fn($item) => $item->total_spent ?? []));
            const linectx = lineChartCanvas.getContext('2d');
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
                        x: {
                            title: {
                                display: true,
                                text: 'Months'
                            },
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Expenses'
                            },
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
        }

        const barChart2Canvas = document.getElementById('barChart2');
        if (barChart2Canvas) {
            const barlabels2 = @json($tag_related_spendings->map(fn($item) => $item->tag));
            const bardata2 = @json($tag_related_spendings->map(fn($item) => $item->total));
            const barsums2 = @json($tag_related_spendings->map(fn($item) => $item->debit_sum));
            const barctx2 = barChart2Canvas.getContext('2d');

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
                        x: {
                            title: {
                                display: true,
                                text: 'Tags'
                            },
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Spendings'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });
        }

        //Horizontal bar chart
        const horizontalBarChartCanvas = document.getElementById('horizontalBarChart');
        if (horizontalBarChartCanvas) {
            const horizontalLabels = @json($percent_changes->map(fn($item) => \Carbon\Carbon::createFromFormat('m', $item['month'])->format('M')));
            const horizontalData = @json($percent_changes->map(fn($item) => $item['changeAbs']));
            const horizontalCtx = horizontalBarChartCanvas.getContext('2d');

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
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Percent Change (%)'
                            },
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Months'
                            },
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });
        }
    </script>
@endsection
