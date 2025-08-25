<?php

namespace App\Http\Controllers;

use App\Models\User;
use Native\Laravel\Facades\Alert;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::query()->select('id', 'name', 'email', 'created_at')->paginate(20);

        return view('admin.index', ['admins' => $admins]);
    }

    public function destroy(string $id)
    {
        try {
            $admin = User::where('id', $id)->first();
            if ($admin) {
                $confirmed = Alert::new()
                    ->title('Are you sure?')
                    ->buttons(['Yes, delete', 'Cancel'])
                    ->show('This will permanently delete Admin and its data. This action cannot be undone.');
                if (! $confirmed) {
                    $admin->delete();
                    Alert::new()
                        ->title('Success')
                        ->show('Admin deleted successfully.');
                }
            } else {
                Alert::new()
                    ->title('Not Found')
                    ->show('Admin is not found.');
            }

            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Deleted successfully',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
