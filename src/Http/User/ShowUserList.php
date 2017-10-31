<?php


namespace MyApp\Http\User;


use MyApp\Models\Authentication\Services\UserService;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;
use function var_dump;

class ShowUserList
{
    /**
     * @var Twig
     */
    private $twig;
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(Twig $twig, UserService $userService)
    {
        $this->twig = $twig;
        $this->userService = $userService;
    }

    public function __invoke(Request $request, Response $response)
    {
        $page = (int)$request->getQueryParam('page', 0);

        $maxpage = $this->userService->getMaxPage();

        $users = $this->userService->getUsers($page);


        return $this->twig->render($response, 'Users/listUsers.twig', [
            'users' => $users,
            'url' => '/users',
            'page' => $page,
            'maxpage' => $maxpage,
            'newUrl' => '/user/new',
            'newLabel' => 'Create User',
            'searchUrl' => '/user/search'
        ]);
    }
}