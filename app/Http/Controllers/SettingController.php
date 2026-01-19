<?php

namespace App\Http\Controllers;

use App\Enum\AiProviderEnum;
use App\Models\User;
use App\Models\UserAiKey;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);
        $userAiKeys = UserAiKey::where('user_id', $user->id)
            ->select('id', 'name', 'api_key', 'provider', 'is_active')
            ->orderByDesc('is_active')
            ->orderByDesc('id')
            ->paginate(5)
            ->fragment('list-section');

        return view('settings.index', [
            'user' => $user,
            'ai_provider' => AiProviderEnum::cases(),
            'user_ai_keys' => $userAiKeys,
        ]);
    }
}
