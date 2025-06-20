<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>

<body>
    <h1>Transaction</h1>
    <form method="post" action="{{route('transaction.import')}}">
        @csrf
        <button type="submit">Import</button>
    </form>
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
</body>

</html>
