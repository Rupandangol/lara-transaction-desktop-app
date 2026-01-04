<?php

namespace App\Http\Controllers;

use App\Jobs\ClearTempTransaction;
use App\Models\TempTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Native\Laravel\Facades\Alert;

class TempTransactionController extends Controller
{
    public function index()
    {
        return view('user.transaction.temp', [
            'data' => TempTransaction::where('user_id', Auth::user()->id)->get(),
        ]);
    }

    public function destroy(int $id)
    {
        $userId = Auth::user()->id;
        $transaction = TempTransaction::where(['user_id' => $userId, 'id' => $id])->first();
        if ($transaction) {
            $confirmed = Alert::new()
                ->title('Are you sure?')
                ->buttons(['Yes, delete', 'Cancel'])
                ->show('This will delete your temp transactions');
            if (! $confirmed) {
                $transaction->delete();
                Alert::new()
                    ->title('Success')
                    ->show('Deleted successfully.');
            }
        } else {
            Alert::new()
                ->title('Not Found')
                ->show('Data is not found.');
        }

        return redirect()->back();
    }

    public function approve()
    {
        $userId = Auth::user()->id;
        $temp = TempTransaction::where('user_id', $userId)->exists();
        if (! $temp) {
            Alert::new()
                ->title('Failed')
                ->show('No Data');

            return redirect()->back();
        }
        $columns = Schema::getColumnListing('temp_transactions');
        DB::table('temp_transactions')->select($columns)->orderBy('date_time')->chunk(1000, function ($q) {
            $fullData = [];
            foreach ($q as $item) {
                $fullData = $this->formatedData($item);
            }
            DB::table('transactions')->insert($fullData);
        });
        ClearTempTransaction::dispatch($userId);
        Alert::new()
            ->title('Success')
            ->show('Transaction Added');

        return redirect(route('transaction.index'));
    }

    public function formatedData($data)
    {
        return [
            'date_time' => $data->date_time,
            'description' => $data->description,
            'debit' => $data->debit,
            'credit' => $data->credit,
            'tag' => $data->tag,
            'status' => $data->status,
            'channel' => $data->channel,
            'user_id' => $data->user_id,
        ];
    }
}
