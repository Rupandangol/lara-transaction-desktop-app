<?php

namespace App\Http\Controllers;

use App\Actions\TransactionActions\GetTransactionsAggregates;
use App\Imports\TransactionImport;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Native\Laravel\Facades\Alert;
use Native\Laravel\Facades\Notification;

class TransactionsController extends Controller
{
    public const BASE_AMOUNT = 0;

    public const TEN_MIN_IN_SEC = 600;

    public function index(Request $request)
    {
        $nowYear = '';
        $query = DB::table('transactions')->where('user_id', Auth::user()->id);
        if ($request->filled('year_month')) {
            $query->whereYear('date_time', Carbon::parse($request->get('year_month'))->format('Y'))
                ->whereMonth('date_time', Carbon::parse($request->get('year_month'))->format('m'));
        }
        if ($request->filled('date')) {
            $query->whereDate('date_time', Carbon::parse($request->get('date'))->format('Y-m-d'));
        }
        if ($request->filled('description')) {
            $query->where('description', 'like', '%'.$request->get('description').'%');
        }
        if ($request->filled('year')) {
            if ($request->get('year') != 'all') {
                $query->whereYear('date_time', $request->get('year'));
                $nowYear = $request->get('year');
            }
        } else {
            $nowYear = Carbon::now()->format('Y');
            $query->whereYear('date_time', $nowYear);
        }
        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $sort_by = $request->get('sort_by');
            $sort_order = $request->get('sort_order');
        }
        $aggregates = app(GetTransactionsAggregates::class)->handle($query, $request, $nowYear);

        $transactions = $query
            ->orderBy($sort_by ?? 'date_time', $sort_order ?? 'desc')
            ->simplePaginate(10)
            ->appends($request->only(['year', 'year_month', 'date', 'description']));
        $data = [
            'transactions' => $transactions,
            ...$aggregates,
        ];

        return view('user.transaction.index', $data);
    }

    public function import(Request $request)
    {
        $validated = $request->validate([
            'transaction_file' => 'required|mimetypes:text/plain,text/csv,application/vnd.ms-excel',
            'source_type' => 'required|in:default,esewa',
        ], [
            'transaction_file.mimetypes' => 'The transaction file must be a valid CSV or Excel file.',
        ]);
        try {
            $uploaded = Excel::import(new TransactionImport($validated['source_type']), $validated['transaction_file']);
            self::cacheFlush();
            if ($uploaded) {
                Notification::title('Import completed')->message('Transaction import completed')->show();

                return back()
                    ->with([
                        'status' => 'success',
                        'message' => 'Uploaded Successful',
                    ]);
            }
            Alert::new()
                ->error('An error occurred', 'The pizza oven is broken');

            return back()
                ->with([
                    'status' => 'error',
                    'message' => 'Upload Failed',
                ]);
        } catch (\Exception $e) {
            Alert::new()->type('error')->title('Error')->show('Try changing source type, or follow as sample provided');

            return redirect()->back();
        }
    }

    public function sample()
    {
        try {
            $file = public_path('statement/Sample.csv');

            return response()->download($file, 'Sample.csv');
        } catch (\Exception $e) {
            Alert::new()->type('error')->title('Error')->show($e->getMessage());

            return redirect()->back();
        }
    }

    public function purge()
    {
        try {
            $confirmed = Alert::new()
                ->title('Are you sure?')
                ->buttons(['Yes, delete', 'Cancel'])
                ->show('This will permanently delete all your transactions. This action cannot be undone.');

            if (! $confirmed) {
                $userId = Auth::id();

                DB::transaction(function () use ($userId) {
                    Transaction::where('user_id', $userId)->delete();
                    self::cacheFlush();
                });

                Alert::new()
                    ->title('Transactions Deleted')
                    ->show('All your transactions have been successfully purged.');
            }
        } catch (\Exception $e) {
            Alert::new()->type('error')->title('Error')->show($e->getMessage());
        }

        return redirect()->back();
    }

    public function create()
    {
        return view('user.transaction.create');
    }

    public static function cacheFlush()
    {
        Cache::flush();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_time' => 'required',
            'description' => 'required|max:255',
            'transaction_type' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'channel' => 'required',
            'tag' => 'sometimes',
        ]);
        try {
            $userId = Auth::user()->id;

            $transaction = Transaction::create([
                'date_time' => $validated['date_time'],
                'description' => $validated['description'],
                $validated['transaction_type'] => $validated['amount'],
                'status' => $validated['status'],
                'channel' => $validated['channel'],
                'tag' => $validated['tag'],
                'user_id' => $userId,
            ]);
            Notification::title('Success')->message('Transaction added successfully')->show();

            return redirect(route('transaction.index'));
        } catch (\Exception $e) {
            Alert::new()
                ->type('error')
                ->title('Error:'.$e->getCode())
                ->show($e->getMessage());
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        $userId = Auth::user()->id;
        $transaction = Transaction::where(['user_id' => $userId, 'id' => $id])->first();
        if ($transaction) {
            $confirmed = Alert::new()
                ->title('Are you sure?')
                ->buttons(['Yes, delete', 'Cancel'])
                ->show('This will permanently delete all your transactions. This action cannot be undone.');
            if (! $confirmed) {
                $transaction->delete();
                Alert::new()
                    ->title('Success')
                    ->show('Transaction deleted successfully.');
            }
        } else {
            Alert::new()
                ->title('Not Found')
                ->show('Transaction is not found.');
        }

        return redirect()->back();
    }

    public function edit($id)
    {
        $userId = Auth::user()->id;
        $transaction = Transaction::where(['id' => $id, 'user_id' => $userId])->first();
        $type = $transaction->debit > self::BASE_AMOUNT ? 'debit' : 'credit';
        $data = [
            'date_time' => $transaction->date_time,
            'description' => $transaction->description,
            'transaction_type' => $type,
            'amount' => $transaction[$type],
            'status' => $transaction->status,
            'channel' => $transaction->channel,
            'tag' => $transaction->tag,
            'id' => $transaction->id,
        ];

        return view('user.transaction.edit', [
            'transaction' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date_time' => 'required',
            'description' => 'required|max:255',
            'transaction_type' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'channel' => 'required',
            'tag' => 'sometimes',
        ]);
        try {
            $userId = Auth::user()->id;
            $transaction = Transaction::where(['id' => $id, 'user_id' => $userId])->first();
            $transaction->update([
                'date_time' => $validated['date_time'],
                'description' => $validated['description'],
                $validated['transaction_type'] => $validated['amount'],
                $validated['transaction_type'] == 'credit' ? 'debit' : 'credit' => self::BASE_AMOUNT,
                'status' => $validated['status'],
                'channel' => $validated['channel'],
                'tag' => $validated['tag'],
                'user_id' => $userId,
            ]);
            Notification::title('Success')->message('Transaction updated successfully')->show();

            return redirect(route('transaction.index'));
        } catch (\Exception $e) {
            Alert::new()
                ->type('error')
                ->title('Error:'.$e->getCode())
                ->show($e->getMessage());
        }

        return redirect()->back();
    }

    public function export()
    {
        $filename = 'transaction_'.Carbon::now()->format('Ymdhsi').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Id',
                'Date and time',
                'Description',
                'Debit(Rs.)',
                'Credit(Rs.)',
                'Tag',
                'Status',
                'Channel',
            ]);
            DB::table('transactions')
                ->select('id', 'date_time', 'description', 'debit', 'credit', 'tag', 'status', 'channel')
                ->where('user_id', Auth::user()->id)
                ->orderByDesc('date_time')
                ->chunk(1000, function ($items) use ($handle) {
                    foreach ($items as $item) {
                        fputcsv($handle, [
                            $item->id,
                            $item->date_time,
                            $item->description,
                            $item->debit,
                            $item->credit,
                            ucfirst($item->tag),
                            $item->status,
                            $item->channel,
                        ]);
                    }
                });
            fclose($handle);
        }, 200, $headers);
    }
}
