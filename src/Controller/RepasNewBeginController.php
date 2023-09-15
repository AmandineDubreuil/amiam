<?php

namespace App\Controller;

use App\Entity\Allergene;
use App\Entity\Regime;
use App\Entity\Repas;
use App\Form\RepasType;
use App\Repository\AllergeneRepository;
use App\Repository\AmiFamilleRepository;
use App\Repository\AmiRepository;
use App\Repository\RecetteRepository;
use App\Repository\RegimeRepository;
use App\Repository\RepasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/repas_')]
class RepasNewBeginController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/new', name: 'app_repas_new_begin', methods: ['GET', 'POST'])]
    public function newBegin(
        Request $request,
        Repas $repas,
        EntityManagerInterface $entityManager,
        AmiFamilleRepository $amiFamilleRepository,
        AmiRepository $amiRepository,
    ): Response {
        $user = $this->security->getUser();
        $familles = $amiFamilleRepository->findBy(['user' => $user]);
        
        $amis = $amiRepository->findBy(['famille'=> $familles ]);

       
            if ($request->isMethod('POST') && $request->request->has('submit')) {

          
            return $this->redirectToRoute('app_repas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repas/new_begin.html.twig', [
            'controller_name' => 'RepasNewBeginController',
            'familles' => $familles,
            'amis' => $amis,
           
        ]);
    }
}
