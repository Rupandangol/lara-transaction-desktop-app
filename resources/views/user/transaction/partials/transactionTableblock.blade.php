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
                      {{-- <th class="border px-4 py-2">Date & Time</th> --}}
                      <th class="border px-4 py-2">
                          @if (request()->get('sort_by') === 'date_time' && request()->get('sort_order') === 'desc')
                              <a
                                  href="{{ request()->fullUrlWithQuery(['sort_by' => 'date_time', 'sort_order' => 'asc']) }}">
                                  Date ↑
                              </a>
                          @else
                              <a
                                  href="{{ request()->fullUrlWithQuery(['sort_by' => 'date_time', 'sort_order' => 'desc']) }}">
                                  Date ↓
                              </a>
                          @endif
                      </th>

                      <th class="border px-4 py-2">Description</th>
                      {{-- <th class="border px-4 py-2">Debit (Rs.)</th> --}}
                      <th class="border px-4 py-2">
                          @if (request()->get('sort_by') === 'debit' && request()->get('sort_order') === 'desc')
                              <a
                                  href="{{ request()->fullUrlWithQuery(['sort_by' => 'debit', 'sort_order' => 'asc']) }}">
                                  Debit (Rs.) ↑
                              </a>
                          @else
                              <a
                                  href="{{ request()->fullUrlWithQuery(['sort_by' => 'debit', 'sort_order' => 'desc']) }}">
                                  Debit (Rs.) ↓
                              </a>
                          @endif
                      </th>
                      <th class="border px-4 py-2">
                          @if (request()->get('sort_by') === 'credit' && request()->get('sort_order') === 'desc')
                              <a
                                  href="{{ request()->fullUrlWithQuery(['sort_by' => 'credit', 'sort_order' => 'asc']) }}">
                                  Credit (Rs.) ↑
                              </a>
                          @else
                              <a
                                  href="{{ request()->fullUrlWithQuery(['sort_by' => 'credit', 'sort_order' => 'desc']) }}">
                                  Credit (Rs.) ↓
                              </a>
                          @endif
                      </th>
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
                          <td class="border px-4 py-2">{{ ucwords(str_replace('_', ' ', $item->tag)) ?? '-' }}</td>
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
  <div class="mt-6 mb-4">
      {{ $transactions->links() }}
  </div>
