<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            $locale = session('locale');
        } else {
            // Auto-detect from browser Accept-Language; fall back to 'en' for anything other than 'lv'
            $locale = $request->getPreferredLanguage(['en', 'lv']) ?? 'en';
        }

        App::setLocale(in_array($locale, ['en', 'lv']) ? $locale : 'en');

        return $next($request);
    }
}
