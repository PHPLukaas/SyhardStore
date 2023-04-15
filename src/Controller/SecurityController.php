<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UsersRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/oublie-pass', name: 'forgotten_password')]
    public function forgottenPassword(Request $request, UsersRepository $usersRepository, TokenGeneratorInterface $tokenGenerator, EntityManagerInterface $entityManager, SendMailService $mail): Response
    {

        $form = $this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on va chercher l'utilisateur par son email
            $user = $usersRepository->findOneByEmail($form->get('email')->getData());

            //on vérifie si on a un utilisateur
            if ($user) {
                //on génère un token de réinitialisation
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                //on génère l'url de réinitialisation du mot de passe
                $url = $this->generateUrl('reset_pass',
                    ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                //on crée les données du mail
                $context = compact('url', 'user');

                //on envoie le mail
                $mail->send(
                    'no-reply@e-commerce.fr',
                    $user->getEmail(),
                    'Réinitialisation de votre mot de passe',
                    'reset_password',
                    $context
                );

                $this->addFlash('success', 'Email envoyé, veuillez vérifier votre boite mail');
                return $this->redirectToRoute('app_login');

            }

            // user est null
            $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }

    #[Route('/oublie-pass/{token}', name: 'reset_pass')]
    public function resetPass(string $token, Request $request, UsersRepository $usersRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        //on vérifie si le token est valide
        $user = $usersRepository->findOneByResetToken($token);

        if ($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //on efface le token
                $user->setResetToken('');
                //on hash le mot de passe
                $user->setPassword($hasher->hashPassword($user, $form->get('password')->getData()));
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe modifié avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }

        $this->addFlash('danger', 'Un problème est survenu, veuillez réessayer');
        return $this->redirectToRoute('app_login');
    }
}
