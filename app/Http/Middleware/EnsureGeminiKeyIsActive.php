<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Native\Laravel\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class EnsureGeminiKeyIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::user()->hasActiveKey()) {
            Alert::new()
                ->type('error')
                ->title('AI not ready')
                ->show('Your AI key is turned off or missing. Please enable it in settings.');

            return redirect(route('settings.index'))->withFragment('list-section');
        }

        return $next($request);
    }
}
