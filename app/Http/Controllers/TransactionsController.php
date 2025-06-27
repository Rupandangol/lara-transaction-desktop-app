<?php

namespace App\Http\Controllers;

use App\Imports\TransactionImport;
use App\Models\Transaction;
use App\Services\LinearRegressionPredictionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Native\Laravel\Facades\Alert;
use Native\Laravel\Facades\Notification;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('transactions')->where('user_id', Auth::user()->id);
        if ($request->filled('year_month')) {
            $query->whereYear('date_time', Carbon::parse($request->get('year_month'))->format('Y'))
                ->whereMonth('date_time', Carbon::parse($request->get('year_month'))->format('m'));
        }
        if ($request->filled('date')) {
            $query->whereDate('date_time', Carbon::parse($request->get('date'))->format('Y-m-d'));
        }
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->get('description') . '%');
        }

        $total_transaction = (clone $query)->count();
        $total_spent = (clone $query)->sum('debit');
        $nowYear = Carbon::now()->format('Y');
        $monthly_expenses = (clone $query)
            ->selectRaw("strftime('%m', date_time) as month, strftime('%Y', date_time) as year, SUM(debit) as total_spent")
            ->groupBy('month')
            ->having('year', $nowYear)
            ->orderBy('month')
            ->get();
        $top_expenses = (clone $query)->selectRaw("description , COUNT(*) as total,SUM(debit) as debit_sum")
            ->where('credit', 0)
            ->groupBy('description')
            ->orderByDesc('debit_sum')
            ->limit(5)
            ->get();
        $linear_regression_forecast = (new LinearRegressionPredictionService())->predict($monthly_expenses);
        $over_all_forecast = (clone $query)
            ->selectRaw("strftime('%m', date_time) as month, strftime('%Y', date_time) as year, SUM(debit) as total_spent")
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
        $three_month_forecast = (clone $query)
            ->selectRaw("strftime('%m', date_time) as month, strftime('%Y', date_time) as year, SUM(debit) as total_spent")
            ->where('date_time', '>=', Carbon::now()->subMonths(3))
            ->groupBy('year', 'month')
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();
        $over_all_time_based_spendings = (clone $query)
            ->selectRaw("strftime('%H', date_time) as hour, COUNT(*) as total, SUM(debit) as sum")
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        $transactions = $query->orderByDesc('date_time')->simplePaginate(10)->appends($request->only(['year_month', 'date', 'description']));
        $data = [
            'transactions' => $transactions,
            'total_transaction' => $total_transaction,
            'total_spent' => $total_spent,
            'top_expenses' => $top_expenses,
            'monthly_expenses' => $monthly_expenses,
            'over_all_forecast' => round($over_all_forecast->avg('total_spent')),
            'three_month_forecast' => round($three_month_forecast->avg('total_spent')),
            'linear_regression_forecast' => round($linear_regression_forecast) ?? 0,
            'over_all_time_based_spendings' => $over_all_time_based_spendings,
        ];
        return view('user.transaction.index', $data);
    }


    public function import(Request $request)
    {
        $validated = $request->validate([
            'transaction_file' => 'required|mimes:xls,csv',
            'source_type' => 'required|in:default,esewa'
        ]);
        try {
            $uploaded = Excel::import(new TransactionImport($validated['source_type']), $validated['transaction_file']);

            if ($uploaded) {
                Notification::title('Import completed')->message('Transaction import completed')->show();
                return back()
                    ->with([
                        'status' => 'success',
                        'message' => 'Uploaded Successful'
                    ]);
            }
            Alert::new()
                ->error('An error occurred', 'The pizza oven is broken');
            return back()
                ->with([
                    'status' => 'error',
                    'message' => 'Upload Failed'
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

            if (!$confirmed) {
                $userId = Auth::id();

                DB::transaction(function () use ($userId) {
                    Transaction::where('user_id', $userId)->delete();
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_time' => 'required',
            'description' => 'required|max:255',
            'transaction_type' => 'required',
            'amount' => 'required',
            'status' => 'required',
            'channel' => 'required',
            'tag' => 'sometimes'
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
                'user_id' => $userId
            ]);
            Notification::title('Success')->message('Transaction added successfully')->show();
            return redirect(route('transaction.index'));
        } catch (\Exception $e) {
            Alert::new()
                ->type('error')
                ->title('Error:' . $e->getCode())
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
            if (!$confirmed) {
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
        $type = $transaction->debit > 0 ? 'debit' : 'credit';
        $data = [
            'date_time' => $transaction->date_time,
            'description' => $transaction->description,
            'transaction_type' => $type,
            'amount' => $transaction[$type],
            'status' => $transaction->status,
            'channel' => $transaction->channel,
            'tag' => $transaction->tag,
            'id' => $transaction->id
        ];
        return view('user.transaction.edit', [
            'transaction' => $data
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
            'tag' => 'sometimes'
        ]);
        try {
            $userId = Auth::user()->id;
            $transaction = Transaction::where(['id' => $userId, 'user_id' => $userId])->first();
            $transaction->update([
                'date_time' => $validated['date_time'],
                'description' => $validated['description'],
                $validated['transaction_type'] => $validated['amount'],
                $validated['transaction_type'] == 'credit' ? 'debit' : 'credit' => 0,
                'status' => $validated['status'],
                'channel' => $validated['channel'],
                'tag' => $validated['tag'],
                'user_id' => $userId
            ]);
            Notification::title('Success')->message('Transaction updated successfully')->show();
            return redirect(route('transaction.index'));
        } catch (\Exception $e) {
            Alert::new()
                ->type('error')
                ->title('Error:' . $e->getCode())
                ->show($e->getMessage());
        }
        return redirect()->back();
    }
}
