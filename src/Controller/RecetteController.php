<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Service\PictureService;
use App\Form\RecetteNewBeginType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


#[Route('/recette')]
class RecetteController extends AbstractController
{
    private $security;
    private $entityManager;
    private $params;


    public function __construct(Security $security, EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->params = $params;
    }

    #[Route('/', name: 'app_recette_index', methods: ['GET'])]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        $recette = new Recette();
        $form = $this->createForm(RecetteNewBeginType::class, $recette);
        $form->handleRequest($request);

        //validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setUser($user);
            $recette->setCreatedAt(new \DateTimeImmutable);
            $recette->setModifiedAt(new \DateTimeImmutable);

            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_edit', [
                'id' => $recette,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'recetteNewBeginForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Recette $recette,
        EntityManagerInterface $entityManager,
        PictureService $pictureService
    ): Response {

        $ingredients = $recette->getIngredients();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setModifiedAt(new \DateTimeImmutable);


            // modification / ajout de la photo
            // on récupère l'image
            $image = $form->get('photo')->getData();
            // on définit le dossier de destination
            $folder = 'photosRecettes';
            if ($image) {
                // on supprime l'ancienne photo si existante
                $oldImage = $recette->getPhoto();
                if ($oldImage) {
                    $oldImagePath = $this->params->get('images_directory') . $folder . '/mini/' . $oldImage;
                    unlink($oldImagePath);
                }
                //on appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);
                $recette->setPhoto($fichier);
            }

            // *****
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_index', [
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'recetteForm' => $form,
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recette->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }
}
