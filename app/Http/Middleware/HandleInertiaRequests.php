<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        dd($request->user());
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? [
                    'id'      => $request->user()->id,
                    'name'    => $request->user()->name,
                    'email' => $request->user()->email,
                    'email'   => $request->user()->email, // useful for notifications or profile
                    // Add only safe-to-share fields
                ] : null,
            ],
            // You can also add global flash messages
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}
