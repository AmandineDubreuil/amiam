<?php

namespace App\Controller;

use App\Entity\Regime;
use App\Repository\AlimentRepository;
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
        AmiFamilleRepository $amiFamilleRepository,
        AmiRepository $amiRepository,
        RecetteRepository $recetteRepository,
        AllergeneRepository $allergeneRepository,
    ): Response {

        $user = $this->security->getUser();
        $familles = $amiFamilleRepository->findBy(['user' => $user]);
        $amis = $amiRepository->findBy(['famille' => $familles]);
        $recettes = $recetteRepository->findBy(['user' => $user]);
        $amisPresents = "";
        $regimes = "";
        $allergiesGroupe = "";
        $recettesAvecAllergene = [];
        $allergiesAliment = "";
        $degouts = "";
        $regimeSsPorc = 0;
        $recettePorc = 0;
        $recettesAvecPorc = [];
        $recetteAEviter = 0;
        $recettesOk = [];


        if ($request->isMethod('POST') && $request->request->has('submit')) {

            ########## DEBUT PARTIE AMIS #########

            // Récupérez les amis sélectionnés
            $amisPresentsId = $request->request->all('amisPourRecettes');


            // Parcourir les amis sélectionnés 
            $amisPresents = $amiRepository->findBy(['id' => $amisPresentsId]);
            foreach ($amisPresents as $amiPresent) {

                //récupérer leurs régimes
                $regimes = $amiPresent->getRegimes();
                foreach ($regimes as $regime) {
                    $regimesPresents[] = $regime;

                    // récupération spécifique régimes sans porc pour filtre des recettes
                    if ($regime->getRegime() === 'Halal') {
                        $regimeSsPorc += 1;
                    }
                    if ($regime->getRegime() === 'Sans porc') {
                        $regimeSsPorc += 1;
                    }
                }

                // récupérer leurs allergies groupe pour filtre des recettes
                $allergiesGroupe = $amiPresent->getAllergies();
                foreach ($allergiesGroupe as $allergieGroupe) {
                    $allergiesGroupePresents[] = $allergieGroupe;
                }

                // récupérer leurs allergies aliment pour filtre des recettes
                $allergiesAliment = $amiPresent->getAllergiesAliment();
                foreach ($allergiesAliment as $al) {
                    $allergiesAlimentPresentes[] = $al;
                }

                //  récupérer leurs dégouts pour filtre des recettes
                $degouts = $amiPresent->getDegout();
                foreach ($degouts as $degout) {
                    $degoutsPresents[] = $degout;
                }

                ############  fin de la boucle amiPresent
            }

            // créer tableau vide si pas d'allergies Aliment, pas de dégout, pas d'allergie groupe
            if (empty($allergiesAlimentPresentes)) {
                $allergiesAlimentPresentes = [];
            }
            if (empty($degoutsPresents)) {
                $degoutsPresents = [];
            }
            if (empty($allergiesGroupePresents)) {
                $allergiesGroupePresents = [];
            }


            ########## DEBUT PARTIE RECETTES #########

            //récupérer les recettes avec allergies, dégouts dans un tableau
            // récupération également des recettes contenant du porc si une personne au moins souhaite sans porc

            foreach ($recettes as $recette) {

                $ingredients = $recette->getIngredients();
                //   récupération des ingrédients de la recette
                foreach ($ingredients as $ingredient) {

                    // récupération ingrédient
                    $aliment = $ingredient->getAliment();
                    $alimentArray[] = $aliment;

                    //récupération allergène
                    $allergene = $aliment->getAllergene();
                    $allergene1stArray = $allergene->toArray();
                    $allergeneString = implode(',', $allergene1stArray);
                    $allergeneArray[] = $allergeneRepository->find($allergeneString);

                    //récupération aliment à base de porc
                    // $recettePorc = 0;
                    $sousGroupeAliment = $aliment->getSousGroupe();
                    $sousGroupeString = $sousGroupeAliment->getSousGroupe();

                    if ($sousGroupeString === 'Porc') {
                        $recettePorc += 1;
                    }
                }


                ######## pour AllergiesAliment #########
                // comparer les ingrédients de la recette aux allergies présentes

                $recettesAvecAllergieAlimentConstruct = array_intersect($alimentArray, $allergiesAlimentPresentes);

                ######## pour Degouts #########
                // comparer les ingrédients de la recette aux dégouts présents

                $recettesAvecDegoutConstruct = array_intersect($alimentArray, $degoutsPresents);

                ######## pour AllergiesGroupe #########
                // comparer les allergenes de la recette aux allergiesGroupe présents
                $recettesAvecAllergeneConstruct = array_intersect($allergeneArray, $allergiesGroupePresents);

                foreach ($ingredients as $ingredient) {
                    $aliment = $ingredient->getAliment();

                    ######## pour AllergiesAliment #########
                    if (!empty($recettesAvecAllergieAlimentConstruct) && in_array($aliment, $recettesAvecAllergieAlimentConstruct)) {
                        // ajouter la recette au tableau $recettesConformes
                        // $recettesAvecAllergieAliment[] = $recette;
                        $recetteAEviter += 1;
                    } else {
                        $recettesAvecAllergieAliment = [];
                    }

                    ######## pour Degouts #########
                    if (!empty($recettesAvecDegoutConstruct) && in_array($aliment, $recettesAvecDegoutConstruct)) {
                        // ajouter la recette au tableau $recettesConformes
                        //   $recettesAvecDegout[] = $recette;
                        $recetteAEviter += 1;
                    } else {
                        $recettesAvecDegout = [];
                    }

                    ######## pour AllergiesGroupe #########
                    $allergene = $aliment->getAllergene();
                    $allergene1stArray = $allergene->toArray();
                    $allergeneString = implode(',', $allergene1stArray);
                    $allergeneEntity = $allergeneRepository->find($allergeneString);

                    if (!empty($recettesAvecAllergeneConstruct) && in_array($allergeneEntity, $recettesAvecAllergeneConstruct)) {
                        // ajouter la recette au tableau $recettesConformes
                        //  $recettesAvecAllergene[] = $recette;
                        $recetteAEviter += 1;
                    }
                }

                ######## pour régimes sans porc et halal #########
                if ($regimeSsPorc > 0) {

                    if ($recettePorc > 0) {
                        $recettePorc = 0;
                        $recetteAEviter += 1;
                    }
                }

                ######## RECUPERATION DES RECETTES OK #########
                dump($recetteAEviter);
                if ($recetteAEviter === 0) {
                    $recettesOk[] = $recette;
                } else {
                    $recetteAEviter = 0;
                }

                ############  fin de la boucle recette
            }
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
            'recettesOk' => $recettesOk,
        ]);
    }
}
