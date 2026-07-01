<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** Supported UI locales. */
    public const SUPPORTED = ['ru', 'en'];

    /**
     * Resolve the UI locale (in order): ?lang → session → cookie → Accept-Language.
     * An explicit ?lang is persisted to session and a long-lived cookie.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolve($request);

        App::setLocale($locale);
        $request->session()->put('locale', $locale);

        // Persist for a year so it survives new sessions. Queue() works for any
        // response type (incl. the SSE StreamedResponse used by the chat).
        Cookie::queue('locale', $locale, 60 * 24 * 365);

        return $next($request);
    }

    private function resolve(Request $request): string
    {
        $query = $request->query('lang');
        if (is_string($query) && in_array($query, self::SUPPORTED, true)) {
            return $query;
        }

        $session = $request->session()->get('locale');
        if (in_array($session, self::SUPPORTED, true)) {
            return $session;
        }

        $cookie = $request->cookie('locale');
        if (in_array($cookie, self::SUPPORTED, true)) {
            return $cookie;
        }

        // First visit: pick Russian only if the browser explicitly prefers it.
        // 'en' is listed first so it is the fallback when nothing matches.
        return $request->getPreferredLanguage(['en', 'ru']);
    }
}
