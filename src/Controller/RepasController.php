<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Form\RepasType;
use App\Form\RepasDateType;
use App\Form\RepasCommentType;
use App\Repository\AmiRepository;
use App\Repository\RepasRepository;
use App\Repository\RecetteRepository;
use App\Repository\AmiFamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/repas')]
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
        $recetteId = $_GET['recetteId'];
        $recette = $recetteRepository->find($recetteId);

        $repa = new Repas();
        $form = $this->createForm(RepasType::class, $repa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repa->setUser($user);
            $repa->setRecettes($recette);
            foreach ($famillesId as $familleId) {
                $famille = $amiFamilleRepository->find($familleId);
                $repa->addAmiFamille($famille);
            }

            $entityManager->persist($repa);
            $entityManager->flush();

            return $this->redirectToRoute('app_repas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repas/new.html.twig', [
            'repa' => $repa,
            'form' => $form,
            'familles' => $famillesId,
            'recette' => $recette,
            'amis' => $amis,
        ]);
    }

    #[Route('/{id}', name: 'app_repas_show', methods: ['GET'])]
    public function show(Repas $repa): Response
    {
        return $this->render('repas/show.html.twig', [
            'repa' => $repa,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_repas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Repas $repa, EntityManagerInterface $entityManager): Response
    {

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

    /*
    #[Route('/{id}/editamis', name: 'app_repas_edit_amis', methods: ['GET', 'POST'])]
    public function editAmis(
        Request $request,
        Repas $repa,
        EntityManagerInterface $entityManager,
        AmiFamilleRepository $amiFamilleRepository,
        AmiRepository $amiRepository,
    ): Response {

        
        $repasId = $repa->getId();
        $user = $this->security->getUser();
        $familles = $amiFamilleRepository->findBy(['user' => $user]);
        $famillesPresentes = [];
        $amis = $amiRepository->findBy(['famille' => $familles]);
        $amisPresents = "";
        $amisPresentsId = "";

        if ($request->isMethod('POST') && $request->request->has('submit')) {

            // Récupérez les amis sélectionnés

            $amisPresentsId = $request->request->all('amisPourRecettes');
            // Parcourir les amis sélectionnés 
            $amisPresents = $amiRepository->findBy(['id' => $amisPresentsId]);
            foreach ($amisPresents as $amiPresent) {
                //récupérer leur famille
                $famillesPresentes[] = $amiPresent->getFamille();
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

        ]);
    }
    
    */

    #[Route('/{id}', name: 'app_repas_delete', methods: ['POST'])]
    public function delete(Request $request, Repas $repa, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $repa->getId(), $request->request->get('_token'))) {
            $entityManager->remove($repa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_repas_index', [], Response::HTTP_SEE_OTHER);
    }
}
