<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAiKeyStoreRequest;
use App\Models\UserAiKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Native\Laravel\Facades\Alert;
use Native\Laravel\Facades\Notification;

class UserAiKeyController extends Controller
{
    public function store(UserAiKeyStoreRequest $request)
    {
        $validated = $request->validated();
        try {
            $this->insertUserAiKey($validated);
            Notification::title('Success')->message('Ai Key added successfully')->show();
        } catch (\Exception $e) {
            Alert::new()
                ->type('error')
                ->title('Error:'.$e->getCode())
                ->show($e->getMessage());
        }

        return redirect()->back()->withFragment('list-section');
    }

    public function insertUserAiKey($validated)
    {
        DB::transaction(function () use ($validated) {
            $userId = Auth::user()->id;

            if ($validated['is_active']) {
                UserAiKey::where('user_id', $userId)
                    ->where('is_active', $validated['is_active'])
                    ->update(['is_active' => false]);
            }
            UserAiKey::create([
                ...$validated,
                'user_id' => $userId,
            ]);
        });
    }

    public function switch(int $id)
    {
        try {
            $userId = Auth::user()->id;
            $userAiKey = UserAiKey::where(['id' => $id, 'user_id' => $userId])->firstOrFail();
            $this->toggleActive($userAiKey, $userId);
            Notification::title('Success')->message('Swtiched successfully')->show();
        } catch (\Exception $e) {
            Alert::new()
                ->type('error')
                ->title('Error:'.$e->getCode())
                ->show($e->getMessage());
        }

        return redirect()->back()->withFragment('list-section');
    }

    public function toggleActive($userAiKey, $userId)
    {
        DB::transaction(function () use ($userAiKey, $userId) {
            $isActive = $userAiKey->is_active;

            if (! $isActive) {
                UserAiKey::where('user_id', $userId)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
            $userAiKey->update([
                'is_active' => ! $userAiKey->is_active,
            ]);
        });
    }

    public function destroy(int $id)
    {
        $userId = Auth::user()->id;
        $userAiKey = UserAiKey::where(['user_id' => $userId, 'id' => $id])->firstOrFail();
        if ($userAiKey) {
            $confirmed = Alert::new()
                ->title('Are you sure?')
                ->buttons(['Yes, delete', 'Cancel'])
                ->show('This will permanently delete your api key. This action cannot be undone.');
            if (! $confirmed) {
                $userAiKey->delete();
                Alert::new()
                    ->title('Success')
                    ->show('Ai key deleted successfully.');
            }
        } else {
            Alert::new()
                ->title('Not Found')
                ->show('Ai key is not found.');
        }

        return redirect()->back()->withFragment('list-section');
    }
}
