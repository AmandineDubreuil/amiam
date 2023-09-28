<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserEmailType;
use App\Repository\AmiFamilleRepository;
use App\Service\PictureService;
use App\Repository\UserRepository;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/editAvatar', name: 'app_user_edit_avatar', methods: ['GET', 'POST'])]
    public function editAvatar(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        PictureService $pictureService
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setModifiedAt(new \DateTimeImmutable);

            // on récupère l'image
            $image = $form->get('avatar')->getData();
            // on définit le dossier de destination
            $folder = 'avatars';
            if ($image) {
                // on supprime l'ancienne image
                $oldImage = $user->getAvatar();
                $pictureService->delete($oldImage, $folder);

                //on appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $user->setAvatar($fichier);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit_avatar.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editEmail', name: 'app_user_edit_email', methods: ['GET', 'POST'])]
    public function editEmail(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        SendMailService $sendMailService,
        JWTService $jWTService
    ): Response {
        $form = $this->createForm(UserEmailType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setModifiedAt(new \DateTimeImmutable);
            //passer isVerified à false puis génération du token de confirmation adresse e-mail :

            $user->setIsVerified(false);

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
            $validity = 86400;
            // générer le token
            $token = $jWTService->generate($header, $payload, $this->getParameter('app.jwtsecret'), $validity);
            //envoi d'un mail
            $sendMailService->send(
                'no-reply@amiam.fr',
                $user->getEmail(),
                'Modification de ton adresse e-mail',
                'modif_email',
                compact('user', 'token')
            );



            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit_email.html.twig', [
            'user' => $user,
            'formEmail' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(
        Request $request,
     User $user, 
     AmiFamilleRepository $amiFamilleRepository,
     EntityManagerInterface $entityManager
     ): Response
    {
        $userId = $user->getId();
        $repas = $user->getRepas();
        $recettes = $user->getRecettes();
        $amiFamilles = $amiFamilleRepository->findByUser($userId);
        dd($amiFamilles);
       $userFamilies = $user->getUserFamilies($userId);

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {

            foreach ($repas as $repa) {
                $entityManager->remove($repa);
            }
            foreach ($recettes as $recette) {
                $entityManager->remove($recette);
            }
            foreach ($amiFamilles as $amiFamille) {
                $entityManager->remove($amiFamille);
             }
            foreach ($userFamilies as $userFamily) {
                $entityManager->remove($userFamily);
            }


            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
