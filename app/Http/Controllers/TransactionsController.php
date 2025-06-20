<?php

namespace App\Http\Controllers;

use App\Imports\TransactionImport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionsController extends Controller
{
    public function index()
    {
        $transactions = Transaction::paginate(10);
        return view('user.transaction.index', ['transactions' => $transactions]);
    }
    public function import(Request $request)
    {
        $file = public_path('/statement/Statement_2025-01-01_to_2025-03-01_2025-05-27_17_25_32.xls');
        if (!$file) {
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
