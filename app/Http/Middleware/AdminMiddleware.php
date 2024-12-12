<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // $user = Auth::user();

        // if (!$user || $user->role !== 'admin') {
        //     return redirect()->route('unauthorized');
        // }
        // return $next($request);
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('unauthorized');
        }

        if ($request->is('api/*')) { // Check if the request is from the API
            if ($user->role === 'mahasiswa') { // Allow mahasiswa role for API requests
                return $next($request);
            } else {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
        } elseif ($request->is('/*')) { // Check if the request is from the website
            if ($user->role === 'admin') { // Allow admin role for website requests
                return $next($request);
            } else {
                return redirect()->route('unauthorized');
            }
        }
        return $next($request);
    }

    // public function handle(Request $request, Closure $next): Response
    // {
    //     $user = Auth::user();

    //     if (!$user) {
    //         return redirect()->route('unauthorized');
    //     }

    //     if ($request->is('api/*')) { // Check if the request is from the API
    //         if ($user->role === 'mahasiswa') { // Allow mahasiswa role for API requests
    //             return $next($request);
    //         } else {
    //             return response()->json(['message' => 'Unauthorized'], 403);
    //         }
    //     } elseif ($request->is('/*')) { // Check if the request is from the website
    //         if ($user->role === 'admin') { // Allow admin role for website requests
    //             return $next($request);
    //         } else {
    //             return redirect()->route('unauthorized');
    //         }
    //     }
    // }
}
