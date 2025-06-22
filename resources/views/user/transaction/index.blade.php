<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>

<body>

    @session('status')
        @if (session('status') == 'success')
            <code>
                @session('message')
                    {{ session('message') }}
                @endsession
            </code>
        @else
            <code>
                @session('message')
                    {{ session('message') }}
                @endsession
            </code>
        @endif
    @endsession
    <h1>Transaction</h1>
    <hr>
    <form method="post" action="{{ route('transaction.import') }}">
        @csrf
        <button type="submit">Import</button>
    </form>
    <hr>
    <form action="{{ route('transaction.index') }}" method="get">
        @csrf
        <div>
            <label for="Month">Month</label>
            <input value="{{ request('year_month') ?? '' }}" name="year_month" type="month">
        </div>
        <div>
            <label for="date">Date</label>
            <input type="date" name="date">
        </div>
        <div>
            <label for="description">Description</label>
            <input type="text" value="{{ old('description') ?? '' }}" name="description">
        </div>
        <button type="submit">Search</button>
        <button type="reset">Clear</button>
    </form>
    <hr>
    <h5>Total transation: {{ $total_transaction }}</h5>
    <h5>Total spent: {{ $total_spent }}</h5>
    <hr>
    <table>
        <thead>
            <tr>
                <th>id</th>
                <th>date_time</th>
                <th>description</th>
                <th>debit</th>
                <th>credit</th>
                <th>tag</th>
                <th>status</th>
                <th>channel</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->date_time }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->debit }}</td>
                    <td>{{ $item->credit }}</td>
                    <td>{{ $item->tag }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->channel }}</td>
                </tr>
            @empty
                <tr>
                    <td>No data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $transactions->links() }}
</body>

</html>
