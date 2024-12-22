<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('selectGuard');
      /*   if (!$request->expectsJson()) {
            $localLang = app()->getLocale();
            if (FacadesRequest::is('/user/dashboard')) {
                return redirect()->route('welcome');
            } elseif (FacadesRequest::is('/admin/dashboard')) {
                return redirect()->route('welcome');
            }  else {
                return redirect()->route('selectGuard');
            }
        }
        return null; */
    }
}
