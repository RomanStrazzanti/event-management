<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthentificatorAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        // Utilise $request->request pour récupérer les données du formulaire
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $csrfToken = $request->request->get('_csrf_token');

        // Enregistrer le dernier email utilisé
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // Créer et retourner le Passport
        return new Passport(
            new UserBadge($email), // Récupère l'utilisateur avec l'email
            new PasswordCredentials($password), // Récupère le mot de passe
            [
                new CsrfTokenBadge('authenticate', $csrfToken), // CSRF token
                new RememberMeBadge(), // Gérer la session "remember me"
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Récupérer la route cible si elle existe
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Si aucune route cible n'est définie, redirige vers la page d'accueil
        return new RedirectResponse($this->urlGenerator->generate('dashboard')); // Remplace 'home' par la route de ton choix
    }

    protected function getLoginUrl(Request $request): string
    {
        // Redirige vers la page de login si l'utilisateur n'est pas authentifié
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
