<?php


namespace MyApp\Http;


use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ShowLogin
{
    /**
     * @var Twig
     */
    private $twig;

    /**
     * ShowLogin constructor.
     *
     * @param Twig $twig
     */
    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response)
    {
        return $this->twig->render($response, 'login.twig');
    }
}