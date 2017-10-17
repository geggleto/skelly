<?php


namespace MyApp\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;

class SecurityMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (isset($_SESSION['user'])) {
            return $next($request, $response);
        }

        return $response->withRedirect('/');
    }
}