<?php

namespace App\Controller;

use App\Entity\AmiFamille;
use App\Form\AmiFamilleType;
use App\Repository\AmiFamilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ami/famille')]
class AmiFamilleController extends AbstractController
{
    #[Route('/', name: 'app_ami_famille_index', methods: ['GET'])]
    public function index(AmiFamilleRepository $amiFamilleRepository): Response
    {
        return $this->render('ami_famille/index.html.twig', [
            'ami_familles' => $amiFamilleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ami_famille_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $amiFamille = new AmiFamille();
        $form = $this->createForm(AmiFamilleType::class, $amiFamille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($amiFamille);
            $entityManager->flush();

            return $this->redirectToRoute('app_ami_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ami_famille/new.html.twig', [
            'ami_famille' => $amiFamille,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ami_famille_show', methods: ['GET'])]
    public function show(AmiFamille $amiFamille): Response
    {
        return $this->render('ami_famille/show.html.twig', [
            'ami_famille' => $amiFamille,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ami_famille_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AmiFamille $amiFamille, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AmiFamilleType::class, $amiFamille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ami_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ami_famille/edit.html.twig', [
            'ami_famille' => $amiFamille,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ami_famille_delete', methods: ['POST'])]
    public function delete(Request $request, AmiFamille $amiFamille, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$amiFamille->getId(), $request->request->get('_token'))) {
            $entityManager->remove($amiFamille);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ami_famille_index', [], Response::HTTP_SEE_OTHER);
    }
}
