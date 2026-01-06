<details class="bg-white rounded-md shadow-md mb-5" {{ $errors->any() ? 'open' : '' }}>
    <summary
        class="cursor-pointer px-5 py-4 p-4 font-semibold text-lg text-gray-800 bg-white hover:bg-gray-300 rounded-md">
        📁 Transaction Upload & Filters
        @php
            $filters = collect([
                'description' => request('description'),
                'year' => request('year')?? now()->format('Y'),
                'year_month' => request('year_month'),
                'hour' => request('hour'),
            ])->filter();
        @endphp

        @if ($filters->isNotEmpty())
            <span class="text-sm text-gray-600 ml-2">
                —
                @foreach ($filters as $label => $value)
                    <span class="mr-2">{{ $label }}: {{ $value }}|</span>
                @endforeach
            </span>
            <a href="{{ route('transaction.index') }}"
                class="ml-2 bg-gray-300 text-black px-2 py-1 rounded text-xs hover:bg-gray-400">Clear</a>
        @endif
    </summary>

    <div class="p-5 border-t border-gray-200">
        <div class="bg-white p-5 shadow-md rounded-md mb-5">
            <div class="flex justify-between">
                <form id="importForm" method="POST" action="{{ route('transaction.import') }}"
                    enctype="multipart/form-data" class="mb-6 ">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="transaction_file">Excel
                            Upload</label>
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
                    <select name="year" id="year"
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                        @for ($year = now()->year; $year >= 2000; $year--)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                        <option value="all" {{ request('year') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input id="date" value="{{ request('date') ?? '' }}" name="date" type="date"
                        class="mt-1 block w-full border border-gray-300 rounded px-3 py-2">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <input id="description" name="description" type="text"
                        value="{{ request('description') ?? '' }}"
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
    </div>
</details>
