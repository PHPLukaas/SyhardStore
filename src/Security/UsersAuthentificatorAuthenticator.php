<?php

namespace App\Security;

use  Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


/**
 * Authenticator pour l'authentification des utilisateurs.
 */
class UsersAuthentificatorAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    /**
     * Construit une nouvelle instance de l'authentificateur.
     *
     * @param UrlGeneratorInterface $urlGenerator L'interface du générateur d'URL.
     */
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * Authentifie l'utilisateur en vérifiant les informations d'identification.
     *
     * @param Request $request La requête.
     *
     * @return Passport Le passeport d'authentification.
     */
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    /**
     * Redirige l'utilisateur après une authentification réussie.
     *
     * @param Request $request La requête.
     * @param TokenInterface $token Le jeton d'authentification.
     * @param string $firewallName Le nom du pare-feu.
     *
     * @return Response|null La réponse de redirection ou null si aucune redirection n'est nécessaire.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
         return new RedirectResponse($this->urlGenerator->generate('app_main'));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    /**
     * Renvoie l'URL de la page de connexion.
     *
     * @param Request $request La requête.
     *
     * @return string L'URL de la page de connexion.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
