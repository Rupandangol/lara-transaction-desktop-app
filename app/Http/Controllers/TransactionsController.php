<?php

namespace App\Http\Controllers;

use App\Imports\TransactionImport;
use App\Models\Transaction;
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
        $top_expenses = (clone $query)->selectRaw("description , COUNT(*) as total,SUM(debit) as debit_sum,SUM(credit) as credit_sum")
            ->groupBy('description')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
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
            'over_all_time_based_spendings' => $over_all_time_based_spendings,
        ];
        return view('user.transaction.index', $data);
    }
    public function import(Request $request)
    {
        $validated = $request->validate([
            'transaction_file' => 'required|mimes:xls'
        ]);
        $uploaded = Excel::import(new TransactionImport, $validated['transaction_file']);

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
    }
    public function sample() {}
}
