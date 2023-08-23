<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\JWTService;
use App\Security\Authenticator;
use App\Security\EmailVerifier;
use App\Service\SendMailService;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, Authenticator $authenticator, EntityManagerInterface $entityManager, SendMailService $sendMailService, JWTService $jWTService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('plainPassword')->getData() === $form->get('confirmPassword')->getData()) {
                $user->setCreatedAt(new \DateTimeImmutable);
                $user->setModifiedAt(new \DateTimeImmutable);
                $user->setRoles(['ROLE_USER']);

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
                // générer le token
                $token = $jWTService->generate($header, $payload, $this->getParameter('app.jwtsecret'));
                //envoi d'un mail
                $sendMailService->send(
                    'no-reply@amiam.fr',
                    $user->getEmail(),
                    'Activation de votre compte Amiam',
                    'register',
                    compact('user', 'token')
                );


                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                );
            } else {
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'passError' => 'Les mots de passe ne sont pas identiques.'
                ]);
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/{token}', name: 'app_verify_user')]
    public function verifyUser($token, JWTService $jWTService, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        // vérifier si le token est valide, n'a pas expiré et n'a pas été modifié
        if ($jWTService->isValidToken($token) && !$jWTService->isExpiredToken($token) && $jWTService->checkSignatureToken($token, $this->getParameter('app.jwtsecret'))) {
            // récupération du payload
            $payload = $jWTService->getPayload($token);
            // récupération du user du token
            $user = $userRepository->find($payload['user_id']);
            //vérification que le user existe et n'a pas encore activé so compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $user->setRoles(['ROLE_MEMBER']);
                $entityManager->flush($user);
                
               // dd($user);
                $this->addFlash('success', 'Félicitations ! Ton compte utilisateur est activé !');
                return $this->redirectToRoute('app_login');
            }
        }
        // ici un problème dans le token
        $this->addFlash('danger', 'Le Token est invalide ou a expiré.');
        return $this->redirectToRoute('app_login');
    }
    // renvoi de la vérification
    #[Route('/renvoiverif', name: 'app_resend_verif')]
    public function resendVerif(JWTService $jWTService, SendMailService $sendMailService, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        // vérifie que l'utilisateur est connecté
        if (!$user) {
            $this->addFlash('danger', 'Tu dois être connecté pour accéder à cette page.');
            return $this->redirectToRoute('app_login');
        }

        //vérifie que l'utilisateur n'a pas déjà été vérifié
        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Ce compte utilisateur est déjà activé.');
            return $this->redirectToRoute('app_user_index');
        }

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

        // générer le token
        $token = $jWTService->generate($header, $payload, $this->getParameter('app.jwtsecret'));

        // dd($token);

        //envoi d'un mail
        $sendMailService->send(
            'no-reply@amiam.fr',
            $user->getEmail(),
            'Activation de ton compte Amiam',
            'register',
            compact('user', 'token')
        );
        $this->addFlash('success', 'Un e-mail vient de t\'être envoyé à l\'adresse que tu nous as communiquée.');
        return $this->redirectToRoute('app_login');
    }
    // private EmailVerifier $emailVerifier;

    // public function __construct(EmailVerifier $emailVerifier)
    // {
    //     $this->emailVerifier = $emailVerifier;
    // }

    // #[Route('/register', name: 'app_register')]
    // public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // encode the plain password
    //         $user->setPassword(
    //             $userPasswordHasher->hashPassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         // generate a signed url and email it to the user
    //         $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
    //             (new TemplatedEmail())
    //                 ->from(new Address('mailer@amiam.fr', 'Amiam Bot'))
    //                 ->to($user->getEmail())
    //                 ->subject('Please Confirm your Email')
    //                 ->htmlTemplate('registration/confirmation_email.html.twig')
    //         );
    //         // do anything else you need here, like send an email

    //         return $this->redirectToRoute('app_user_show');
    //     }

    //     return $this->render('registration/register.html.twig', [
    //         'registrationForm' => $form->createView(),
    //     ]);
    // }

    // #[Route('/verify/email', name: 'app_verify_email')]
    // public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    // {
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    //     // validate email confirmation link, sets User::isVerified=true and persists
    //     try {
    //         $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
    //     } catch (VerifyEmailExceptionInterface $exception) {
    //         $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

    //         return $this->redirectToRoute('app_register');
    //     }

    //     // @TODO Change the redirect on success and handle or remove the flash message in your templates
    //     $this->addFlash('success', 'Your email address has been verified.');

    //     return $this->redirectToRoute('app_register');
    // }
}
