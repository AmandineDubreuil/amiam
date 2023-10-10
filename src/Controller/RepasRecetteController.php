<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Entity\Repas;
use App\Repository\RecetteRepository;
use App\Repository\RepasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RepasRecetteController extends AbstractController
{
    #[Route('/detail-recette-du-repas', name: 'app_repas_recette')]
    public function index(
        RecetteRepository $recetteRepository,
        RepasRepository $repasRepository,
    ): Response {
        $recetteId = $_GET['id'];
        $recette = $recetteRepository->find($recetteId);

        if ($recette->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        $ingredients = $recette->getIngredients();

        $repasId = $_GET['repas'];
        $repa = $repasRepository->find($repasId);
        $nbCouverts = 1;

        return $this->render('repas_recette/index.html.twig', [
            'controller_name' => 'RepasRecetteController',
            'recette' => $recette,
            'repa' => $repa,
            'nbCouverts' => $nbCouverts,
            'ingredients' => $ingredients
        ]);
    }
}
