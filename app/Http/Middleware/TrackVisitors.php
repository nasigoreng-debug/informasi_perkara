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
        try {
            $ip = $request->ip();
            $date = now()->toDateString();

            \App\Models\Visitor::firstOrCreate([
                'ip_address' => $ip,
                'visit_date' => $date
            ], [
                'user_agent' => $request->userAgent()
            ]);
        } catch (\Exception $e) {
            // Jika DB error, catat di log saja, jangan hentikan aplikasi
            \Log::warning("Gagal mencatat visitor: " . $e->getMessage());
        }

        return $next($request);
    }
}
