<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    // 🟢 DYNAMIC REDIRECT BASED ON ROLE
    $role = $request->user()->role;

    switch ($role) {
        case 'admin':
            return redirect()->intended(route('dashboard'));
        case 'sale':
            return redirect()->intended(route('pos.index')); // Sales staff go straight to POS
        case 'stock':
            return redirect()->intended(route('products.index')); // Stock managers go to Products
        default:
            return redirect()->intended('/'); // Fallback
    }
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
