<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Aspect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next)
    {
        $route = $request->route();
        $controller = null;
        $method = null;

        // Extract the controller and method from the route action
        if ($route) {
            $action = $route->getAction();
            if (isset($action['controller'])) {
                [$controller, $method] = explode('@', $action['controller']);
            }
        }

        $this->executeBefore($request, $controller, $method);

        try {
            $response = $next($request);

            $this->executeAfter($request, $controller, $method, $response);

            return $response;
        } catch (\Exception $exception) {
            // Execute the "exception" hook
            $this->executeException($request, $controller, $method, $exception);

            throw $exception; // Re-throw exception
        }
    }

    // functions
    public function executeBefore($request, $controller, $method)
    {
        Log::info('Before Request Execution...');
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('Request Method: ' . $request->method());
        Log::info('Request IP: ' . $request->ip());
        Log::info('Request Parameters: ' . json_encode($request->all()));
        Log::info('Controller: ' . ($controller ?: 'N/A'));
        Log::info('Method in Controller: ' . ($method ?: 'N/A'));
        Log::info('........................................................... ');

    }

    public function executeAfter($request, $controller, $method, $response)
    {
        Log::info('After Request Execution...');
        Log::info('Controller: ' . ($controller ?: 'N/A'));
        Log::info('Method in Controller: ' . ($method ?: 'N/A'));
        Log::info('Response Status Code: ' . $response->getStatusCode());

        if ($response->isRedirect()) {
            Log::info('Redirecting to: ' . $response->getTargetUrl());
        }
        Log::info('Response Content: ' . $response->getContent());

    }

    public function executeException($request, $controller, $method, $exception)
    {
        Log::error('Exception Occurred...');
        Log::error('Controller: ' . ($controller ?: 'N/A'));
        Log::error('Method: ' . ($method ?: 'N/A'));
        Log::error('Request URL: ' . $request->fullUrl());
        Log::error('Request Parameters: ' . json_encode($request->all()));
        Log::error('Exception Message: ' . $exception->getMessage());
        Log::error('Stack Trace: ' . $exception->getTraceAsString());
    }
}
