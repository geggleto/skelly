<?php


namespace MyApp\Middleware;


use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class SecurityMiddleware
{
    /**
     * @var Twig
     */
    private $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, $next)
    {
        if (isset($_SESSION['user'])) {
            $this->twig->getEnvironment()->addGlobal('user', $_SESSION['user']);
            return $next($request, $response);
        }

        return $response->withRedirect('/');
    }
}