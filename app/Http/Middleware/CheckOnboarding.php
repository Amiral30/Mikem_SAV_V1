<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->isTechnicien() && !$user->onboarding_completed) {
            // Liste des routes autorisées pendant l'onboarding
            $allowedRoutes = [
                'technicien.onboarding.show',
                'technicien.onboarding.verify',
                'technicien.onboarding.resend',
                'technicien.onboarding.password',
                'technicien.onboarding.update-password',
                'logout'
            ];

            $routeName = $request->route() ? $request->route()->getName() : null;

            if ($routeName && !in_array($routeName, $allowedRoutes)) {
                return redirect()->route('technicien.onboarding.show');
            }
        }

        return $next($request);
    }
}
