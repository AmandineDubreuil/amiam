<?php

namespace App\Controller;

use App\Entity\UserFamily;
use App\Form\UserFamilyType;
use App\Repository\UserFamilyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user_family')]
#[IsGranted('ROLE_USER')]

class UserFamilyController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'app_user_family_index', methods: ['GET'])]
    public function index(UserFamilyRepository $userFamilyRepository): Response
    {
        return $this->render('user_family/index.html.twig', [
            'user_families' => $userFamilyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_family_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->security->getUser();

        $userFamily = new UserFamily();
        $form = $this->createForm(UserFamilyType::class, $userFamily);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userFamily->setUser($userId);
            $entityManager->persist($userFamily);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_family/new.html.twig', [
            'user_family' => $userFamily,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_family_show', methods: ['GET'])]
    public function show(UserFamily $userFamily): Response
    {
        if ($userFamily->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_family/show.html.twig', [
            'user_family' => $userFamily,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_family_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserFamily $userFamily, EntityManagerInterface $entityManager): Response
    {
        if ($userFamily->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(UserFamilyType::class, $userFamily);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_family/edit.html.twig', [
            'user_family' => $userFamily,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_family_delete', methods: ['POST'])]
    public function delete(Request $request, UserFamily $userFamily, EntityManagerInterface $entityManager): Response
    {
        if ($userFamily->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        
        if ($this->isCsrfTokenValid('delete' . $userFamily->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userFamily);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
