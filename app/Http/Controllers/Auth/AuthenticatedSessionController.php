<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
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

        $user = $request->user();
        $basicInformation = $user->basicInformation;

        $usingDefaultPassword =
            $basicInformation?->issuedId?->employee_id === '1000001'
            && $basicInformation?->birth_date?->format('mdY') === $request->password;

        if ($usingDefaultPassword) {
            $status = Password::sendResetLink(['email' => $user->email]);

            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'status',
                $status === Password::RESET_LINK_SENT
                    ? 'You must change your default password. A reset link has been sent to your account email.'
                    : 'You must change your default password. Please contact the administrator if you cannot receive a reset link.'
            );
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}