<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommunauteController extends AbstractController
{
    #[Route('/communaute', name: 'app_communaute_index')]
    public function index(RecetteRepository $recetteRepository): Response
    {


        return $this->render('communaute/index.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }

    #[Route('/communaute/{id}', name: 'app_communaute_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        if ($recette->isPrive()=== true) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $ingredients = $recette->getIngredients();
        return $this->render('communaute/show.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
        ]);
    }
}
