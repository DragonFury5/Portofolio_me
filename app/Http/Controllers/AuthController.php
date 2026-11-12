<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string', 'max:190'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $identifier = Str::lower(trim($credentials['login']));
        $throttleKey = $identifier.'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'login' => "Too many attempts. Try again in {$seconds} seconds.",
            ]);
        }

        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $identifier, 'password' => $credentials['password']], $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'login' => 'That email or username and password do not match an account.',
            ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))
            ->with('status', 'Welcome back. The harbour is where you left it.');
    }

    public function showRegister(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register', [
            'selectedTemplate' => in_array($request->query('template'), ['ledger', 'atlas', 'signal', 'tide'], true)
                ? $request->query('template')
                : null,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge([
            'username' => Str::lower(trim((string) $request->input('username'))),
            'email' => Str::lower(trim((string) $request->input('email'))),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:60'],
            'username' => [
                'required',
                'string',
                'regex:/^[a-z0-9](?:[a-z0-9_]{1,22})[a-z0-9]$/',
                Rule::notIn(reserved_slugs()),
                Rule::unique(User::class, 'username'),
            ],
            'email' => ['required', 'email', 'max:190', Rule::unique(User::class, 'email')],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
            'terms' => ['accepted'],
        ], [
            'username.regex' => 'Use 3–24 lowercase letters, numbers or underscores. Start and end with a letter or number.',
            'username.not_in' => 'That username is reserved. Pick another.',
            'terms.accepted' => 'Please agree to the house rules before creating an account.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('studio.portfolios.create', array_filter([
            'template' => $request->query('template'),
        ]))->with('status', 'Your studio is ready. Pick a starting point for your first page.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'Logged out. Your pages are still here when you come back.');
    }
}
