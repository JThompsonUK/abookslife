<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'username' => Auth::user() ? Auth::user()->name : null,

            'auth' => Auth::user() ? [
                'user' => [
                    'username' => Auth::user()->name
                ]
            ] : null,

            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                ];
            },

//            'flash' => [
//                'message' => fn () => $request->session()->get('message'),
//                'success' => fn () => $request->session()->get('success'),
//                'errors' => fn () => $request->session()->get('errors'),
//                'error' => fn () => $request->session()->get('error')
//            ],
        ]);
    }
}
