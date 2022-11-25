<?php

declare(strict_types=1);

namespace KingsCode\DevTools\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Carbon\Carbon;

abstract class BaseDevToolsController extends Controller
{
    private const DEV_TOOLS_AUTH_KEY = 'auth';
    private const DEFAULT_LIFE_SPAN = 60 * 60; // 1 hour

    public abstract function showOverview(Request $request): View;

    public function auth(Request $request): View
    {
        [$result, $message] = $this->isAuthorised($request);
        if (! $result) {
            return $this->showLogin($request, $message);
        }

        return $this->showOverview($request);
    }

    public function isAuthorised(Request $request): array
    {
        $authTime = $request->session()?->get(self::DEV_TOOLS_AUTH_KEY);
        if ($authTime !== null) {
            $isValid = Carbon::parse($authTime)->isAfter(now());

            if (! $isValid) {
                $request->session()->remove(self::DEV_TOOLS_AUTH_KEY);
            }

            return [Carbon::parse($authTime)->isAfter(now()), $isValid ? null : 'Session expired, please re-enter PIN'];
        }

        $pin = data_get($request->input('pin'), 'value');
        if ($pin === env('DEV_TOOLS_PIN')) {
            $request->session()->put(self::DEV_TOOLS_AUTH_KEY, now()->addSeconds(self::DEFAULT_LIFE_SPAN));

            return [true, null];
        }

        return [false, $pin === null ? null : 'The provided PIN is invalid'];
    }

    public function logout(Request $request): View
    {
        $request->session()->remove(self::DEV_TOOLS_AUTH_KEY);

        return $this->showLogin($request);
    }

    public function showLogin(Request $request, string $message = null): View
    {
        return view('kc-dev-tools::login', [
            'request_uri'  => $request->getRequestUri(),
            'auth_message' => $message,
        ]);
    }
}