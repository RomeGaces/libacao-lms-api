<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentAccess
{
    protected $departmentLandingPages = [
        'LM001' => '/hr-dashboard',
        'LM005' => '/budget-dashboard',
        'LM006' => '/accounting-dashboard',
        'LM007' => '/treasury-dashboard',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->employee || !$user->employee->department) {
            abort(403, 'Access denied.');
        }

        $deptCode = $user->employee->department->department_code;

        foreach ($this->departmentLandingPages as $code => $route) {
            if ($deptCode !== $code && $request->is(ltrim($route, '/'))) {
                abort(403, 'Access denied.');
            }
        }

        if ($request->routeIs('home') || $request->routeIs('login')) {
            return redirect($this->departmentLandingPages[$deptCode]);
        }

        return $next($request);
    }
}
