<?php

namespace App\Controller;

use App\Entity\Regime;
use App\Repository\AllergeneRepository;
use App\Repository\AmiRepository;
use App\Repository\RecetteRepository;
use App\Repository\AmiFamilleRepository;
use App\Repository\RecetteIngredientRepository;
use App\Repository\RegimeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPUnit\Framework\isEmpty;

class RepasnewController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/repasnew', name: 'app_repasnew')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        AmiFamilleRepository $amiFamilleRepository,
        AmiRepository $amiRepository,
        RecetteRepository $recetteRepository,
        RecetteIngredientRepository $recetteIngredientRepository,
    ): Response {

        $user = $this->security->getUser();
        $familles = $amiFamilleRepository->findBy(['user' => $user]);
        $amis = $amiRepository->findBy(['famille' => $familles]);
        $recettes = $recetteRepository->findBy(['user' => $user]);
        $amisPresents = "";
        $regimes = "";
        $allergiesGroupe = "";
        $allergiesAliment = "";
        $degouts = "";

        if ($request->isMethod('POST') && $request->request->has('submit')) {

            // Récupérez les amis sélectionnés
            $amisPresentsId = $request->request->all('amisPourRecettes');


            // Parcourir les amis sélectionnés 
            $amisPresents = $amiRepository->findBy(['id' => $amisPresentsId]);
            foreach ($amisPresents as $amiPresent) {
                //récupérer leurs régimes
                $regimes = $amiPresent->getRegimes();

                foreach ($regimes as $regime) {
                    $regimesPresents[] = $regime;
                }

                // récupérer leurs allergies groupe
                $allergiesGroupe = $amiPresent->getAllergies();

                foreach ($allergiesGroupe as $allergieGroupe) {
                    $allergiesGroupePresents[] = $allergieGroupe;
                }

                // récupérer leurs allergies aliment
                $allergiesAliment = $amiPresent->getAllergiesAliment();

                foreach ($allergiesAliment as $al) {
                    $allergiesAlimentPresentes[] = $al;
                }

              //  récupérer leurs dégouts
                $degouts = $amiPresent->getDegout();
                    foreach ($degouts as $degout) {
                        $degoutsPresents[] = $degout;
                    }

                ############  fin de la boucle amiPresent
            }

            // créer tableau vide si pas d'allergies Aliment, pas de dégout
            if (empty($allergiesAlimentPresentes)) {
                $allergiesAlimentPresentes = [];
            }
            if (empty($degoutsPresents)) {
                $degoutsPresents = [];
            }



            //récupérer les recettes avec allergies, dégouts, régime sans porc et régime Halal dans un tableau
            foreach ($recettes as $recette) {

                $ingredients = $recette->getIngredients();
                //   récupération des ingrédients de la recette
                foreach ($ingredients as $ingredient) {
                    $aliment = $ingredient->getAliment();
                    $alimentArray[] = $aliment;
                }

                ######## pour AllergiesAliment #########
                // comparer les ingrédients de la recette aux allergies présentes

                $recettesAvecAllergieAlimentConstruct = array_intersect($alimentArray, $allergiesAlimentPresentes);

                ######## pour Degouts #########
                // comparer les ingrédients de la recette aux dégouts présents

                 $recettesAvecDegoutConstruct = array_intersect($alimentArray, $degoutsPresents);

                foreach ($ingredients as $ingredient) {
                    $aliment = $ingredient->getAliment();

                    ######## pour AllergiesAliment #########
                    if (!empty($recettesAvecAllergieAlimentConstruct) && in_array($aliment, $recettesAvecAllergieAlimentConstruct)) {
                        // ajouter la recette au tableau $recettesConformes
                        $recettesAvecAllergieAliment[] = $recette;
                    } else {
                        $recettesAvecAllergieAliment = [];
                    }

                    ######## pour Degouts #########
                    if (!empty($recettesAvecDegoutConstruct) && in_array($aliment, $recettesAvecDegoutConstruct)) {
                        // ajouter la recette au tableau $recettesConformes
                        $recettesAvecDegout[] = $recette;
                    } else {
                        $recettesAvecDegout = [];
                    }
                }

                // récupérer les recettes sans présence d'allergie ou de dégout

                ######## pour AllergiesAliment #########
                if (!in_array($recette, $recettesAvecAllergieAliment)) {
                    // ajouter la recette au tableau $recettesConformes
                    $recettesOkAllergiesAliment[] = $recette;
                }

                ######## pour Degouts #########
                if (!in_array($recette, $recettesAvecDegout)) {
                    // ajouter la recette au tableau $recettesConformes
                    $recettesOkDegout[] = $recette;
                }



                ############  fin de la boucle recette
            }



            dd($recettesOkDegout);



            ############  fin du submit en dessous
        }



        return $this->render('repasnew/index.html.twig', [
            'controller_name' => 'RepasnewController',
            'familles' => $familles,
            'amis' => $amis,
            'amisPresents' => $amisPresents,
            'regimes' => $regimes,
            'allergiesGroupe' => $allergiesGroupe,
            'allergiesAliment' => $allergiesAliment,
            'recettes' => $recettes,
        ]);
    }
}
