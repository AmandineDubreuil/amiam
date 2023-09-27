<?php

namespace App\Controller;

use App\Entity\Ami;
use App\Entity\AmiFamille;
use App\Form\AmiFamilleType;
use App\Repository\AmiFamilleRepository;
use App\Repository\AmiRepository;
use App\Repository\RepasRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[Route('/amifamille')]
class AmiFamilleController extends AbstractController
{
    private $security;
    private $params;


    public function __construct(Security $security, ParameterBagInterface $params)
    {
        $this->security = $security;
        $this->params = $params;
    }

    #[Route('/', name: 'app_ami_famille_index', methods: ['GET'])]
    public function index(AmiFamilleRepository $amiFamilleRepository): Response
    {
        return $this->render('ami_famille/index.html.twig', [
            'ami_familles' => $amiFamilleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ami_famille_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        AmiFamille $amiFamille,
        EntityManagerInterface $entityManager,
        PictureService $pictureService
    ): Response {
        $user = $this->security->getUser();

        $amiFamille = new AmiFamille();
        $form = $this->createForm(AmiFamilleType::class, $amiFamille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $amiFamille->setUser($user);

            // modification / ajout de la photo
            // on récupère l'image
            $image = $form->get('avatar')->getData();
            // on définit le dossier de destination
            $folder = 'photosAmisFamille';
            if ($image) {

                //on appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 170, 170);
                $amiFamille->setAvatar($fichier);
            }
            // *****

            $entityManager->persist($amiFamille);
            $entityManager->flush();

            return $this->redirectToRoute('app_ami_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ami_famille/new.html.twig', [
            'ami_famille' => $amiFamille,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ami_famille_show', methods: ['GET'])]
    public function show(AmiFamille $amiFamille): Response
    {
        return $this->render('ami_famille/show.html.twig', [
            'ami_famille' => $amiFamille,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ami_famille_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        AmiFamille $amiFamille,
        EntityManagerInterface $entityManager,
        PictureService $pictureService,
    ): Response {
        $form = $this->createForm(AmiFamilleType::class, $amiFamille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on récupère l'image
            $image = $form->get('avatar')->getData();
            // on définit le dossier de destination
            $folder = 'photosAmisFamille';
            if ($image) {
                // on supprime l'ancienne image
                $oldImage = $amiFamille->getAvatar();
                if ($oldImage) {
                    $pictureService->delete($oldImage, $folder);
                }
                //on appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 170, 170);

                $amiFamille->setAvatar($fichier);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_ami_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ami_famille/edit.html.twig', [
            'ami_famille' => $amiFamille,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ami_famille_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        AmiFamille $amiFamille,
        AmiRepository $amiRepository,
        EntityManagerInterface $entityManager,
    ): Response {

        $familleId = $amiFamille->getId();
        $amis = $amiRepository->findByFamille($familleId);
        $repas = $amiFamille->getRepas();
        if ($this->isCsrfTokenValid('delete' . $amiFamille->getId(), $request->request->get('_token'))) {

            foreach ($amis as $ami) {
                $entityManager->remove($ami);
            }
            foreach ($repas as $repa) {
                $entityManager->remove($repa);
            }
            $entityManager->remove($amiFamille);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ami_famille_index', [], Response::HTTP_SEE_OTHER);
    }
}
