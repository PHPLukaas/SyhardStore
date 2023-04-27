<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthentificatorAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthentificatorAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            /**
             * Génération du JWT de l'utilisateur
             * on créé le header
             * on créé le payload
             * on génère le token
             */

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];
            $payload = [
                'id' => $user->getId(),
            ];

            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            //On envoie un mail de confirmation
            $mail->send(
                'no-replay@monsite.net',
                $user->getEmail(),
                'Activation de votre compte, vous allez devenir un Syhardien',
                'register',
                compact('user', 'token')
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser($token, JWTService $jwt, UsersRepository $usersRepository, EntityManagerInterface $em): Response
    {
        //on verifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if ($jwt->isValid($token) && !$jwt->isExpired($token) &&
            $jwt->check($token, $this->getParameter('app.jwtsecret'))) {

            // récupération du payload
            $payload = $jwt->getPayload($token);

            // on récupère le users du token
            $user = $usersRepository->find($payload['id']);

            //on vérifie que l'utilisateur existe et n'a pas déjà été activé son compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('succes', 'Compte activé avec succès');
                return $this->redirectToRoute('profil_index');
            }

        }

        // Problème ce pose dans le token
        $this->addFlash('danger', 'Le token est invalide ou invalide');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/renvoieverif', name: 'renvoie_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail, UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Votre compte est déjà activé');
            return $this->redirectToRoute('profil_index');
        }

        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];
        $payload = [
            'id' => $user->getId(),
        ];

        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        //On envoie un mail de confirmation
        $mail->send(
            'no-replay@monsite.net',
            $user->getEmail(),
            'Activation de votre compte, vous allez devenir un Syhardien',
            'register',
            compact('user', 'token')
        );

        $this->addFlash('succes', 'Un mail de vérification vous a été envoyé');
        return $this->redirectToRoute('profil_index');
    }
}
