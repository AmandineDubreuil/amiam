<?php

namespace App\Controller;

use App\Entity\Ami;
use App\Entity\Recette;
use App\Entity\Repas;
use App\Form\RepasType;
use App\Form\RepasDateType;
use App\Form\RepasCommentType;
use App\Repository\AmiRepository;
use App\Repository\RepasRepository;
use App\Repository\RecetteRepository;
use App\Repository\AllergeneRepository;
use App\Repository\AmiFamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/repas')]
#[IsGranted('ROLE_USER')]

class RepasController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_repas_index', methods: ['GET'])]
    public function index(RepasRepository $repasRepository): Response
    {
        return $this->render('repas/index.html.twig', [
            'repas' => $repasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_repas_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        AmiFamilleRepository $amiFamilleRepository,
        AmiRepository $amiRepository,
        RecetteRepository $recetteRepository,
    ): Response {

        $user = $this->security->getUser();
        $amisId = $_GET['amisId'];
        foreach ($amisId as $amiId) {
            $ami = $amiRepository->find($amiId);
            $amis[] = $ami;
        }

        $famillesId = $_GET['famillesPresentes'];
        $recettesId = $_GET['recettesChoisies'];
        foreach ($recettesId as $recetteId) {
            $recette = $recetteRepository->find($recetteId);
            $recettes[] = $recette;
        }

        $repa = new Repas();
        $form = $this->createForm(RepasType::class, $repa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repa->setUser($user);
            foreach ($recettes as $recetteR) {

                $repa->addRecette($recetteR);
            }
            foreach ($famillesId as $familleId) {
                $famille = $amiFamilleRepository->find($familleId);
                $repa->addAmiFamille($famille);
            }
            foreach ($amisId as $amiId) {
                $ami = $amiRepository->find($amiId);
                $repa->addAmi($ami);
            }
            $entityManager->persist($repa);
            $entityManager->flush();

            return $this->redirectToRoute('app_repas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repas/new.html.twig', [
            'repa' => $repa,
            'form' => $form,
            'familles' => $famillesId,
            'recettes' => $recettes,
            'amis' => $amis,
        ]);
    }

    #[Route('/{id}', name: 'app_repas_show', methods: ['GET'])]
    public function show(Repas $repa): Response
    {
        if ($repa->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $nbCouverts = 1;
        $recetteRepa = $repa->getRecettes();
        $ingredients = $recetteRepa->getIngredients();

        return $this->render('repas/show.html.twig', [
            'repa' => $repa,
            'ingredients' => $ingredients,
            'nbCouverts' => $nbCouverts,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_repas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repas $repa, EntityManagerInterface $entityManager): Response
    {

        if ($repa->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(RepasType::class, $repa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_repas_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repas/edit.html.twig', [
            'repa' => $repa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editdate', name: 'app_repas_edit_date', methods: ['GET', 'POST'])]
    public function editDate(Request $request, Repas $repa, EntityManagerInterface $entityManager): Response
    {
        if ($repa->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $repasId = $repa->getId();

        $form = $this->createForm(RepasDateType::class, $repa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_repas_show', [
                'id' => $repasId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repas/edit_date.html.twig', [
            'repa' => $repa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editcomment', name: 'app_repas_edit_comment', methods: ['GET', 'POST'])]
    public function editComment(Request $request, Repas $repa, EntityManagerInterface $entityManager): Response
    {
        if ($repa->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $repasId = $repa->getId();

        $form = $this->createForm(RepasCommentType::class, $repa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_repas_show', [
                'id' => $repasId,
            ], Response::HTTP_SEE_OTHER);
        }
    }
    #[Route('/{id}/editrecette', name: 'app_repas_edit_recette', methods: ['GET', 'POST'])]
    public function editRecette(
        Request $request,
        Repas $repa,
        EntityManagerInterface $entityManager,
        RecetteRepository $recetteRepository,
        AllergeneRepository $allergeneRepository,
    ): Response {

        if ($repa->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }


        $user = $this->security->getUser();

        $repasId = $repa->getId();
        $amis = $repa->getAmis();
        $recettes = $recetteRepository->findBy(['user' => $user]);

        $allergiesGroupe = "";
        $allergiesAliment = "";
        $regimes = "";
        $degouts = "";
        $regimeSsPorc = 0;
        $recettePorc = 0;
        $recetteAEviter = 0;
        $recettesOk = [];

        foreach ($amis as $ami) {


            // récupérer leurs allergies groupe pour filtre des recettes
            $allergiesGroupe = $ami->getAllergies();
            foreach ($allergiesGroupe as $allergieGroupe) {
                $allergiesGroupePresents[] = $allergieGroupe;

                $this->addFlash('danger', $ami->getPrenom() . ' est allergique à :' . $allergieGroupe);
            }

            // récupérer leurs allergies aliment pour filtre des recettes
            $allergiesAliment = $ami->getAllergiesAliment();
            foreach ($allergiesAliment as $al) {
                $allergiesAlimentPresentes[] = $al;

                $this->addFlash('danger', $ami->getPrenom() . ' est allergique à : ' . $al);
            }
            //récupérer leurs régimes
            $regimes = $ami->getRegimes();
            foreach ($regimes as $regime) {
                $regimesPresents[] = $regime;

                // récupération spécifique régimes sans porc pour filtre des recettes
                if ($regime->getRegime() === 'Halal') {
                    $regimeSsPorc += 1;
                }
                if ($regime->getRegime() === 'Sans porc') {
                    $regimeSsPorc += 1;
                }
                $this->addFlash('warning', $ami->getPrenom() . ' a un régime ' . $regime);
            }


            //  récupérer leurs dégouts pour filtre des recettes
            $degouts = $ami->getDegout();
            foreach ($degouts as $degout) {
                $degoutsPresents[] = $degout;
                $this->addFlash('warning-jaune', $ami->getPrenom() . ' n\'aime pas : ' . $degout);
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
                    $recetteAEviter += 1;
                }

                ######## pour Degouts #########
                if (!empty($recettesAvecDegoutConstruct) && in_array($aliment, $recettesAvecDegoutConstruct)) {
                    $recetteAEviter += 1;
                }

                ######## pour AllergiesGroupe #########
                $allergene = $aliment->getAllergene();
                $allergene1stArray = $allergene->toArray();
                $allergeneString = implode(',', $allergene1stArray);
                $allergeneEntity = $allergeneRepository->find($allergeneString);

                if (!empty($recettesAvecAllergeneConstruct) && in_array($allergeneEntity, $recettesAvecAllergeneConstruct)) {
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
            $recetteId = $request->request->get('recetteId');
            $recette = $recetteRepository->find($recetteId);
            // dd($recette);

            $repa->setRecettes($recette);
            $entityManager->persist($repa);
            $entityManager->flush();

            return $this->redirectToRoute('app_repas_show', [
                'id' => $repasId,

            ], Response::HTTP_SEE_OTHER);
        }
        return $this->render('repas/edit_recette.html.twig', [
            'repa' => $repa,
            'recettesOk' => $recettesOk,

        ]);
    }

    #[Route('/{id}/editamis', name: 'app_repas_edit_amis', methods: ['GET', 'POST'])]
    public function editAmis(
        Request $request,
        Repas $repa,
        EntityManagerInterface $entityManager,
        AmiFamilleRepository $amiFamilleRepository,
        AmiRepository $amiRepository,
        Ami $ami,
    ): Response {

        if ($repa->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $repasId = $repa->getId();
        $user = $this->security->getUser();

        // afficher les familles et amis déjà enregistrés
        $familles = $amiFamilleRepository->findBy(['user' => $user]);
        $oldFamillesPresentes = $repa->getAmiFamilles();
        $amis = $amiRepository->findBy(['famille' => $familles]);
        $oldAmisPresents = $repa->getAmis();
        $amisPresentsId = "";


        if ($request->isMethod('POST') && $request->request->has('submit')) {
            //reset les familles et amis


            foreach ($oldAmisPresents as $oldAmiPresent) {
                $repa->removeAmi($oldAmiPresent);
            }

            foreach ($oldFamillesPresentes as $oldFamille) {
                $repa->removeAmiFamille($oldFamille);
            }


            // Récupérez les amis sélectionnés
            $amisPresentsId = $request->request->all('amisPourRecettes');

            // Parcourir les amis sélectionnés 
            $amisPresents = $amiRepository->findBy(['id' => $amisPresentsId]);
            // dd($amisPresents);
            foreach ($amisPresents as $amiPresent) {
                //récupérer leur famille
                $famillesPresentes[] = $amiPresent->getFamille();
                $repa->addAmi($amiPresent);
            }


            foreach ($famillesPresentes as $famille) {
                $repa->addAmiFamille($famille);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_repas_show', [
                'id' => $repasId,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repas/edit_amis.html.twig', [
            'repa' => $repa,
            'familles' => $familles,
            'oldFamillesPresentes' => $oldFamillesPresentes,
            'oldAmisPresents' => $oldAmisPresents

        ]);
    }



    #[Route('/{id}', name: 'app_repas_delete', methods: ['POST'])]
    public function delete(Request $request, Repas $repa, EntityManagerInterface $entityManager): Response
    {
        if ($repa->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }


        if ($this->isCsrfTokenValid('delete' . $repa->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_repas_index', [], Response::HTTP_SEE_OTHER);
    }
}
