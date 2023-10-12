<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\RecetteIngredient;
use App\Form\RecetteType;
use App\Service\PictureService;
use App\Form\RecetteNewBeginType;
use App\Repository\RecetteIngredientRepository;
use App\Repository\RecetteRepository;
use App\Repository\RepasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/recette')]
#[IsGranted('ROLE_USER')]

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
            $recette->setPrive(true);
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
        if ($recette->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $ingredients = $recette->getIngredients();
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Recette $recette,
        EntityManagerInterface $entityManager,
        PictureService $pictureService
    ): Response {

        if ($recette->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $idRecette = $recette->getId();
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
                // on supprime l'ancienne image
                $oldImage = $recette->getPhoto();
                if ($oldImage) {
                    $pictureService->delete($oldImage, $folder);
                }
                //on appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);
                $recette->setPhoto($fichier);
            }

            // *****
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_show', [
                'id' => $idRecette,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'recetteForm' => $form,
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Recette $recette,
        RecetteIngredientRepository $recetteIngredientRepository,
        PictureService $pictureService,
        EntityManagerInterface $entityManager,
    ): Response {

        if ($recette->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $recetteId = $recette->getId();
        $recetteIngredients = $recetteIngredientRepository->findByRecette($recetteId);
        $repas = $recette->getRepas();



        if ($this->isCsrfTokenValid('delete' . $recette->getId(), $request->request->get('_token'))) {

            //suppression de l'image associée à la recette
            $oldImage = $recette->getPhoto();
            if ($oldImage) {
                $folder = 'photosRecettes';
                $pictureService->delete($oldImage, $folder);
            }

            //suppression des ingrédients associés à la recette
            foreach ($recetteIngredients as $recetteIngredient) {
                $entityManager->remove($recetteIngredient);
            }

            // suppression de la recette dans les repas associés
            foreach ($repas as $repa) {

                $repaRecettes = $repa->getRecettes();
                foreach ($repaRecettes as $repaRecette) {
                    if ($repaRecette === $recette) {
                        $entityManager->remove($recette);
                    }
                }
            }

            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }
}
