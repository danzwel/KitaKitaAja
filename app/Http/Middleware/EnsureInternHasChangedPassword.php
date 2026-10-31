<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\InternshipStatusService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInternHasChangedPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('intern')) {
            $request->user('intern')->loadMissing('internshipApplication');
            app(InternshipStatusService::class)->syncFor($request->user('intern'));
        }

        if ($request->user('intern') && !is_null($request->user('intern')->temporary_initial_password)) {
            return redirect()->route('intern.password.change');
        }

        return $next($request);
    }
}
