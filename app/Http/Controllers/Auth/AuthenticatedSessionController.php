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
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 
                'Your account has been deactivated. Please contact support.'
            );
        }

        return $this->redirectToDashboard($user);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    protected function redirectToDashboard($user): RedirectResponse
    {
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'marketing' => redirect()->route('admin.dashboard'),
            'developer' => redirect()->route('developer.dashboard'),
            'service_provider' => redirect()->route('service-provider.dashboard'),
            default => redirect()->route('home'),
        };
    }
}