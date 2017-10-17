<?php


namespace MyApp\Http;


use MyApp\Core\MailService;
use MyApp\Models\Authentication\Entities\User;
use MyApp\Models\Authentication\Repositories\UserRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ResetEmail
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var MailService
     */
    private $mailService;
    /**
     * @var Twig
     */
    private $twig;

    public function __construct(
        UserRepository $userRepository,
        MailService $mailService,
        Twig $twig
    )
    {
        $this->userRepository = $userRepository;
        $this->mailService = $mailService;
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response)
    {
        $email = $request->getParsedBodyParam('email');

        $user = $this->userRepository->findByEmail($email);
        if ($user instanceof User) {

            $data = $user->toArray();
            $data['token'] = $user->generateToken();
            $this->userRepository->storeUser($user);

            $html = $this->twig->fetch('email/resetPassword.twig', $data);

            $this->mailService->sendHtml(
                $user->getEmail(),
                'do-not-reply@'.$this->mailService->getDomain(),
                'Password Reset Notice',
                $html
            );

            return $response->withJson(
                [
                    'message' => 'Success'
                ]
            );
        }

        return $response->withStatus(404);
    }
}