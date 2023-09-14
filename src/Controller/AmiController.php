<?php

namespace App\Controller;

use App\Entity\Ami;
use App\Form\AmiType;
use App\Entity\AmiFamille;
use App\Service\PictureService;
use App\Repository\AmiRepository;
use App\Repository\AmiFamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/ami')]
class AmiController extends AbstractController
{
    #[Route('/', name: 'app_ami_index', methods: ['GET'])]
    public function index(AmiRepository $amiRepository): Response
    {
        return $this->render('ami/index.html.twig', [
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
        $form = $this->createForm(AmiType::class, $ami);
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
        return $this->render('ami/show.html.twig', [
            'ami' => $ami,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ami_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Ami $ami,
        EntityManagerInterface $entityManager,
    ): Response {
        $familleId = $ami->getFamille();

        $form = $this->createForm(AmiType::class, $ami);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ami_famille_show', [
                'id' => $familleId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ami/edit.html.twig', [
            'ami' => $ami,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ami_delete', methods: ['POST'])]
    public function delete(Request $request, Ami $ami, EntityManagerInterface $entityManager): Response
    {
        $familleId = $ami->getFamille();

        if ($this->isCsrfTokenValid('delete' . $ami->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ami);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ami_famille_show', [
            'id' => $familleId,
        ], Response::HTTP_SEE_OTHER);
    }
}
