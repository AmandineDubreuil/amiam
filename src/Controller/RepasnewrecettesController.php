<?php

namespace App\Controller;

use App\Repository\AmiRepository;
use App\Repository\RecetteRepository;
use App\Repository\AllergeneRepository;
use App\Repository\AmiFamilleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class RepasnewrecettesController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/repas-new-recettes', name: 'app_repasnewrecette')]
    #[IsGranted('ROLE_USER')]

    public function index(
        Request $request,
        RecetteRepository $recetteRepository,
        AmiRepository $amiRepository,
        AllergeneRepository $allergeneRepository
    ): Response {

        $user = $this->security->getUser();
        $recettes = $recetteRepository->findBy(['user' => $user]);
        $recettesOk = [];
        $amisPresentsId = $_GET['amisPresentsId'];
        $famillesPresentes = $_GET['famillesPresentes'];
        foreach ($amisPresentsId as $amiPresentId) {
            $amiPresent = $amiRepository->find($amiPresentId);
            $amisPresents[] = $amiPresent;
        }
        $regimes = "";
        $allergiesGroupe = "";
        $allergiesAliment = "";
        $degouts = "";
        $regimeSsPorc = 0;
        $recettePorc = 0;
        $recetteAEviter = 0;




        ########## DEBUT PARTIE RECUP INFOS AMIS #########
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




        if ($request->isMethod('POST') && $request->request->has('submit')) {

            $recettesChoisies = $request->request->all('recetteChoisie');

            return $this->redirectToRoute('app_repas_new', [
                'amisId' => $amisPresentsId,
                'famillesPresentes' => $famillesPresentes,
                'recettesChoisies' => $recettesChoisies,
            ], Response::HTTP_SEE_OTHER);


            ############  fin du submit en dessous
        }



        return $this->render('repasnew/indexnewrecettes.html.twig', [
            'controller_name' => 'RepasnewrecettesController',
            'famillesPresentes' => $famillesPresentes,
            'amisId' => $amisPresentsId,
            'amisPresents' => $amisPresents,
            'regimes' => $regimes,
            'allergiesGroupe' => $allergiesGroupe,
            'allergiesAliment' => $allergiesAliment,
            'recettes' => $recettes,
            'recettesOk' => $recettesOk,
        //    'recettesChoisies' => $recettesChoisies,
        ]);
    }
}
