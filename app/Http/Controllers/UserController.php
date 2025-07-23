<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Native\Laravel\Facades\Alert;
use Native\Laravel\Facades\Notification;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string', 'min:5', 'max:20'],
            'new_password' => ['required', 'string', 'min:5', 'max:20'],
        ]);
        $userId = Auth::user()->id;
        $user = User::findOrFail($userId);
        if (Hash::check($validated['current_password'], $user->password)) {
            $confirmed = Alert::new()
                ->title('Are you sure?')
                ->buttons(['Yes', 'Cancel'])
                ->show('This will change your password..');
            if (! $confirmed) {
                $user->update([
                    'password' => Hash::make($validated['new_password']),
                ]);
                Notification::title('Success')
                    ->message('Password Updated Successfully')
                    ->show();
            }
        } else {
            Alert::new()->type('error')->show('Incorrect Current password');
        }

        return redirect()->back();
    }
}
