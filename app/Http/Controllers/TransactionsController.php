<?php

namespace App\Http\Controllers;

use App\Imports\TransactionImport;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TransactionsController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('transactions');
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
        $top_expenses = (clone $query)->selectRaw("description , COUNT(*) as total,SUM(debit) as debit_sum,SUM(credit) as credit_sum")
            ->groupBy('description')
            ->orderByDesc('total')
            ->limit(3)
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
            ->selectRaw("strftime('%h', date_time) as hour, COUNT(*) as total, SUM(debit) as sum")
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        $transactions = $query->simplePaginate(30);
        $data = [
            'transactions' => $transactions,
            'total_transaction' => $total_transaction,
            'total_spent' => $total_spent,
            'top_expenses' => $top_expenses,
            'over_all_forecast' => $over_all_forecast,
            'three_month_forecast' => $three_month_forecast,
            'over_all_time_based_spendings' => $over_all_time_based_spendings,
        ];
        return view('user.transaction.index', $data);
    }
    public function import(Request $request)
    {
        // $file = public_path('/statement/Statement_2025-01-01_to_2025-03-01_2025-05-27_17_25_32.xls');
        $file = public_path('/statement/Statement_2025-05-28_to_2025-05-31_2025-06-05_15_01_44.xls');
        if (!$file) {
            return back()
                ->with([
                    'status' => 'error',
                    'message' => 'file doesnt exists or issue on file'
                ]);
        }
        if (!is_file($file)) {
            return back()
                ->with([
                    'status' => 'error',
                    'message' => 'file doesnt exists or issue on file'
                ]);
        }
        $uploaded = Excel::import(new TransactionImport, $file);
        if ($uploaded) {
            return back()
                ->with([
                    'status' => 'success',
                    'message' => 'Uploaded Successful'
                ]);
        }
        return back()
            ->with([
                'status' => 'error',
                'message' => 'Upload Failed'
            ]);
    }
}
