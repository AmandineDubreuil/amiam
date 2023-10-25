<?php

namespace App\Controller;

use App\Entity\Ami;
use App\Form\AmiType;
use App\Form\AmiNewType;
use App\Service\PictureService;
use App\Repository\AmiRepository;
use App\Repository\AmiFamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/ami')]
#[IsGranted('ROLE_USER')]
class AmiController extends AbstractController
{

    #[Route('/', name: 'app_ami_index', methods: ['GET'])]
    public function index(AmiRepository $amiRepository): Response
    {
        return $this->render('ami_famille/index.html.twig', [
            'amis' => $amiRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ami_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        AmiFamilleRepository $amiFamilleRepository,
        PictureService $pictureService,

    ): Response {
        $familleId = $_GET['famille'];
        $famille = $amiFamilleRepository->find($familleId);

        $ami = new Ami();
        $form = $this->createForm(AmiNewType::class, $ami);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ami->setFamille($famille);

            // on récupère l'image
            $image = $form->get('avatar')->getData();
            // on définit le dossier de destination
            $folder = 'photosAmis';
            if ($image) {

                //on appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 100, 100);
                //$avatar = new Images();
                $ami->setAvatar($fichier);
            }

            $entityManager->persist($ami);
            $entityManager->flush();

            return $this->redirectToRoute('app_ami_famille_show', [
                'id' => $familleId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ami/new.html.twig', [
            'ami' => $ami,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'app_ami_show', methods: ['GET'])]
    public function show(Ami $ami): Response
    {
        if ($ami->getFamille()->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $age = $ami->getAge();


        return $this->render('ami/show.html.twig', [
            'ami' => $ami,
            'age' => $age,
            ]);
    }

    #[Route('/{id}/edit', name: 'app_ami_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Ami $ami,
        EntityManagerInterface $entityManager,
        PictureService $pictureService,
    ): Response {
        if ($ami->getFamille()->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $familleId = $ami->getFamille();
        $amiId = $ami->getId();
        $form = $this->createForm(AmiType::class, $ami);
        $form->handleRequest($request);

        // dd($ami);
        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère l'image
            $image = $form->get('avatar')->getData();
            // on définit le dossier de destination
            $folder = 'photosAmis';
            if ($image) {
                // on supprime l'ancienne image
                $oldImage = $ami->getAvatar();
                if ($oldImage) {
                    $pictureService->delete($oldImage, $folder);
                }
                //on appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 100, 100);

                $ami->setAvatar($fichier);
            }


            $entityManager->flush();

            return $this->redirectToRoute('app_ami_famille_show', [
                'id' => $familleId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ami/edit.html.twig', [
            'ami' => $ami,
            'amiId' => $amiId,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ami_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Ami $ami,
        PictureService $pictureService,
        EntityManagerInterface $entityManager
    ): Response {
        if ($ami->getFamille()->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $familleId = $ami->getFamille();

        if ($this->isCsrfTokenValid('delete' . $ami->getId(), $request->request->get('_token'))) {

            //suppression de l'image associée à la recette
            $oldImage = $ami->getAvatar();
            if ($oldImage) {
                $folder = 'photosAmis';
                $pictureService->delete($oldImage, $folder);
            }


            $entityManager->remove($ami);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ami_famille_show', [
            'id' => $familleId,
        ], Response::HTTP_SEE_OTHER);
    }
}
