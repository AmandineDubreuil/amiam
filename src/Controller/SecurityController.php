<?php

namespace App\Controller;

use App\Service\JWTService;
use App\Form\ResetPasswordType;
use App\Security\Authenticator;
use App\Service\SendMailService;
use App\Repository\UserRepository;
use App\Form\ResetPasswordRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class SecurityController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error])      
        ;

        $this->redirectToRoute('app_home');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/modif-pass', name: 'app_modif_password')]
    public function modificationPassword(
        Request $request,
        UserRepository $userRepository,
        JWTService $jWTService,
        EntityManagerInterface $entityManager,
        SendMailService $sendMailService,
        UserAuthenticatorInterface $userAuthenticator,
        Authenticator $authenticator
    ): Response {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        $user = $this->security->getUser();
        // Génération du JWT de l'utilisateur
        // créer le header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];
        //créer le payload
        $payload = [
            'user_id' => $user->getId()
        ];
        //définir la durée de validité en nb de secondes
        $validity = 1200;
        // générer le token
        $token = $jWTService->generate($header, $payload, $this->getParameter('app.jwtsecret'), $validity);
        //envoi d'un mail
        $sendMailService->send(
            'no-reply@amiam.fr',
            $user->getEmail(),
            'Demande de modification du mot de passe',
            'modif_password',
            compact('user', 'token')
        );

        $this->addFlash('warning', 'Un lien t\'as été envoyé par e-mail pour que tu puisse changer ton mot de passe. Attention, il n\'est valable que 20 minutes.');
        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );
    }

    #[Route(path: '/oubli-pass', name: 'app_forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UserRepository $userRepository,
        JWTService $jWTService,
        EntityManagerInterface $entityManager,
        SendMailService $sendMailService,
        UserAuthenticatorInterface $userAuthenticator,
        Authenticator $authenticator
    ): Response {
        $form = $this->createForm(ResetPasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

        $user = $userRepository->findOneByEmail($form->get('email')->getData());
        
        //dd($user);
        
        // Génération du JWT de l'utilisateur
        // créer le header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];
        //créer le payload
        $payload = [
            'user_id' => $user->getId()
        ];
        //définir la durée de validité en nb de secondes
        $validity = 1200;
        // générer le token
        $token = $jWTService->generate($header, $payload, $this->getParameter('app.jwtsecret'), $validity);
        //envoi d'un mail
        $sendMailService->send(
            'no-reply@amiam.fr',
            $user->getEmail(),
            'Demande de modification du mot de passe',
            'modif_password',
            compact('user', 'token')
        );

        $this->addFlash('warning', 'Si l\'adresse e-mail communiquée est reconnue, un lien t\'as été envoyé par e-mail pour que tu puisse changer ton mot de passe. Attention, il n\'est valable que 20 minutes.');
        return $userAuthenticator->authenticateUser(
            $user,
            $authenticator,
            $request
        );
    }
        // // si $user est nul
        // $this->addFlash('danger', 'Un problème est survenu.');
        // return $this->redirectToRoute('app_login');
        return $this->render('security/reset_password_request.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }
    



    #[Route(path: '/modif-pass/{token}', name: 'app_new_password')]
    public function newPassword(
        $token,
        JWTService $jWTService,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    ): Response {

        // vérifier si le token est valide, n'a pas expiré et n'a pas été modifié
        if ($jWTService->isValidToken($token) && !$jWTService->isExpiredToken($token) && $jWTService->checkSignatureToken($token, $this->getParameter('app.jwtsecret'))) {
            // récupération du payload
            $payload = $jWTService->getPayload($token);
            // récupération du user du token
            $user = $userRepository->find($payload['user_id']);

            if ($user) {
                $form = $this->createForm(ResetPasswordType::class);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $user->setPassword(
                        $passwordHasher->hashPassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    );
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash('success', 'Mot de passe changé avec succès !');
                    return $this->redirectToRoute('app_home');
                }
            }
        }
        return $this->render('security/reset_password.html.twig', [
            'passForm' => $form->createView()
        ]);
    }
}
