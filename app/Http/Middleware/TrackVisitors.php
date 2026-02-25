<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $date = now()->toDateString();

        // Simpan hanya jika IP tersebut belum berkunjung hari ini (agar tidak spam)
        \App\Models\Visitor::firstOrCreate([
            'ip_address' => $ip,
            'visit_date' => $date
        ], [
            'user_agent' => $request->userAgent()
        ]);

        return $next($request);
    }
}
