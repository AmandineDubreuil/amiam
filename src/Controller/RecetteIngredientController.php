<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\RecetteIngredient;
use App\Form\RecetteIngredientType;
use App\Repository\RecetteIngredientRepository;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recetteIngredient')]
#[IsGranted('ROLE_USER')]

class RecetteIngredientController extends AbstractController
{
    #[Route('/', name: 'app_recette_ingredient_index', methods: ['GET'])]
    public function index(RecetteIngredientRepository $recetteIngredientRepository): Response
    {
        return $this->render('recette_ingredient/index.html.twig', [
            'recette_ingredients' => $recetteIngredientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recette_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RecetteRepository $recetteRepository
    ): Response
    {
       

        $recetteId = $_GET['recette'];
        $recette = $recetteRepository->find($recetteId);
  
      $recetteIngredient = new RecetteIngredient();
        $form = $this->createForm(RecetteIngredientType::class, $recetteIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recetteIngredient -> setRecette($recette);
            $entityManager->persist($recetteIngredient);
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_edit', [
                'id' => $recetteId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette_ingredient/new.html.twig', [
            'recette_ingredient' => $recetteIngredient,
            'form' => $form,
            'recette' =>$recetteId,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_ingredient_show', methods: ['GET'])]
    public function show(RecetteIngredient $recetteIngredient): Response
    {

        if ($recetteIngredient->getRecette()->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette_ingredient/show.html.twig', [
            'recette_ingredient' => $recetteIngredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecetteIngredient $recetteIngredient, EntityManagerInterface $entityManager): Response
    {
        if ($recetteIngredient->getRecette()->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $recette = $recetteIngredient->getRecette();
        $form = $this->createForm(RecetteIngredientType::class, $recetteIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_edit', [
                'id' => $recette,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette_ingredient/edit.html.twig', [
            'recette_ingredient' => $recetteIngredient,
            'form' => $form,
            'recette' => $recette,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, RecetteIngredient $recetteIngredient, EntityManagerInterface $entityManager): Response
    {
        if ($recetteIngredient->getRecette()->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        $recette = $recetteIngredient->getRecette();

        if ($this->isCsrfTokenValid('delete'.$recetteIngredient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recetteIngredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recette_edit', [
            'id' => $recette,
        ], Response::HTTP_SEE_OTHER);
    }
}
