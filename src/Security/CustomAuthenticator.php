<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class CustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly RouterInterface $router,
    ){}

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username');
        $plaintextPassword = $request->request->get('_password');
        if (null === $username) {
            throw new CustomUserMessageAuthenticationException('No username provided');
        }

        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $username]);

        if (null === $user) {
            throw new CustomUserMessageAuthenticationException('User not found');
        }

        return new Passport(new UserBadge(
            $user->getId()),
            new PasswordCredentials($plaintextPassword));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($target = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($target);
        }

        return new RedirectResponse('/');
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }

}
