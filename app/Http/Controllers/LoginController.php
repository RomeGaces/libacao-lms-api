<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        Log::info('Login attempt started', [
            'input' => $request->only('email')
        ]);

        $credentials = $request->validate([
            'email'   => ['required', 'string'],
            'password'  => ['required', 'string'],
        ]);

        // check if "remember" checkbox was set
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            Log::info('Login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'remember' => $remember,
            ]);

            $deptCode = $user->employee->department->department_code;
            $route = $this->getDepartmentRoute($deptCode);

            return redirect()->intended($route);
        }

        Log::warning('Login failed', [
            'email' => $request->email,
        ]);

        return back()->withErrors([
            'email' => 'The provided Email or password is incorrect.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(route('login'));
    }

    private function getDepartmentRoute($code)
    {
        $mapping = [
            'LM001' => '/hr-dashboard',
            'LM005' => '/budget-dashboard',
            'LM006' => '/accounting-dashboard',
            'LM007' => '/treasury-dashboard',
        ];

        return $mapping[$code] ?? '/';
    }
}
