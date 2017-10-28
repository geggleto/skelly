<?php


namespace MyApp\Http;


use function session_regenerate_id;
use Slim\Http\Request;
use Slim\Http\Response;

class LogoutUser
{
    public function __invoke(Request $request, Response $response)
    {
        unset($_SESSION['user']);
        session_regenerate_id();

        return $response->withRedirect('/');
    }
}