<?php

namespace App\Controller;

use App\Repository\AllergeneRepository;
use App\Repository\AmiRepository;
use App\Repository\RecetteRepository;
use App\Repository\AmiFamilleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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

        // partie récap amis présents et choix recettes

        $user = $this->security->getUser();
        $familles = $amiFamilleRepository->findBy(['user' => $user]);
        $famillesPresentes = [];
        $amis = $amiRepository->findBy(['famille' => $familles]);
        $recettes = $recetteRepository->findBy(['user' => $user]);
        $amisPresents = "";
        $amisPresentsId = "";
        $regimes = "";
        $allergiesGroupe = "";
        $allergiesAliment = "";
        $degouts = "";
        $regimeSsPorc = 0;
        $recettePorc = 0;
        $recetteAEviter = 0;
        $recettesOk = [];

        // partie récap recettes choisies
    
        $recettesChoisies = [];

        if ($request->isMethod('POST') && $request->request->has('submit')) {

            ########## DEBUT PARTIE AMIS #########

            // Récupérez les amis sélectionnés
            $amisPresentsId = $request->request->all('amisPourRecettes');


            // Parcourir les amis sélectionnés 
            $amisPresents = $amiRepository->findBy(['id' => $amisPresentsId]);
            foreach ($amisPresents as $amiPresent) {
                //récupérer leur famille
                $famillesPresentes[] = $amiPresent->getFamille();
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
                    $this->addFlash('warning', $amiPresent->getPrenom() . ' a un régime ' . $regime);
                }

                // récupérer leurs allergies groupe pour filtre des recettes
                $allergiesGroupe = $amiPresent->getAllergies();
                foreach ($allergiesGroupe as $allergieGroupe) {
                    $allergiesGroupePresents[] = $allergieGroupe;
                    $this->addFlash('danger', $amiPresent->getPrenom() . ' est allergique à :' . $allergieGroupe);
                }

                // récupérer leurs allergies aliment pour filtre des recettes
                $allergiesAliment = $amiPresent->getAllergiesAliment();
                foreach ($allergiesAliment as $al) {
                    $allergiesAlimentPresentes[] = $al;
                    $this->addFlash('danger', $amiPresent->getPrenom() . ' est allergique à : ' . $al);
                }

                //  récupérer leurs dégouts pour filtre des recettes
                $degouts = $amiPresent->getDegout();
                foreach ($degouts as $degout) {
                    $degoutsPresents[] = $degout;
                    $this->addFlash('warning-jaune', $amiPresent->getPrenom() . ' n\'aime pas : ' . $degout);
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
                    }

                    ######## pour Degouts #########
                    if (!empty($recettesAvecDegoutConstruct) && in_array($aliment, $recettesAvecDegoutConstruct)) {
                        // ajouter la recette au tableau $recettesConformes
                        //   $recettesAvecDegout[] = $recette;
                        $recetteAEviter += 1;
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
                if ($recetteAEviter === 0) {
                    $recettesOk[] = $recette;
                } else {
                    $recetteAEviter = 0;
                }

                ############  fin de la boucle recette
            }


            ############  fin du submit en dessous
        }

        // if ($this->isCsrfTokenValid('choix' . $recettes->getId(), $request->request->get('_token'))) {
          
        //     dd($recetteChoix);
        // }


        if ($request->isMethod('POST') && $request->request->has('submitChoixRecette')) {

          //  dd('yata');

                    ############  fin du submitChoixRecette en dessous
        }

        return $this->render('repasnew/index.html.twig', [
            'controller_name' => 'RepasnewController',
            'familles' => $familles,
            'famillesPresentes' => $famillesPresentes,
            'amis' => $amis,
            'amisId' => $amisPresentsId,
            'amisPresents' => $amisPresents,
            'regimes' => $regimes,
            'allergiesGroupe' => $allergiesGroupe,
            'allergiesAliment' => $allergiesAliment,
            'recettes' => $recettes,
            'recettesOk' => $recettesOk,
            'recettesChoisies' =>$recettesChoisies,
        ]);
    }
}
