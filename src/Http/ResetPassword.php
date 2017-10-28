<?php


namespace MyApp\Http;


use MyApp\Core\MailService;
use MyApp\Models\Authentication\Entities\User;
use MyApp\Models\Authentication\Repositories\UserRepository;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\Twig;

class ResetPassword
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var Twig
     */
    private $twig;
    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(
        UserRepository $userRepository,
        Twig $twig,
        MailService $mailService)
    {
        $this->userRepository = $userRepository;
        $this->twig           = $twig;
        $this->mailService    = $mailService;
    }

    public function __invoke($token, Request $request, Response $response)
    {
        $user = $this->userRepository->findByToken($token);
        if ($user instanceof User) {
            $password         = $user->regeneratePassword();
            $data             = $user->toArray();
            $data['password'] = $password;

            $html = $this->twig->fetch('email/passwordReset.twig', $data);

            $this->mailService->sendHtml(
                $user->getEmail(),
                'do-not-reply@' . $this->mailService->getDomain(),
                'Password Reset Successful',
                $html
            );

            $this->userRepository->storeUser($user);

            return $response->withJson([
                                           'message' => 'Success',
                                       ]);
        }

        return $response->withStatus(404);
    }
}