<?php

namespace App\Controller;

use App\Form\RepasDateType;
use App\Repository\AmiRepository;
use App\Repository\RecetteRepository;
use App\Repository\AllergeneRepository;
use App\Repository\AmiFamilleRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RepasnewController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/repas-new-amis', name: 'app_repasnew')]
    #[IsGranted('ROLE_USER')]
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
        $amisPresents = [];
        $amisPresentsId = "";

        if ($request->isMethod('POST') && $request->request->has('submit')) {
            ########## DATE DU REPAS #########
            $dateRepas = $request->request->get('dateRepas');
            //echap si pas de date sélectionnés 
            if (empty($dateRepas)) {
                $this->addFlash('danger', 'Merci de choisir une date pour le repas');
                return $this->render('repasnew/index.html.twig', [
                    'controller_name' => 'RepasnewController',
                    'familles' => $familles,
                    'famillesPresentes' => $famillesPresentes,
                    'amis' => $amis,
                    'amisId' => $amisPresentsId,
                    'amisPresents' => $amisPresents,
                ]);
            }
            ########## DEBUT PARTIE AMIS #########

            // Récupérez les amis sélectionnés
            $amisPresentsId = $request->request->all('amisPourRecettes');


            //echap si pas d'amis sélectionné 
            if (empty($amisPresentsId)) {
                $this->addFlash('danger', 'Merci de choisir au moins un ami');
                return $this->render('repasnew/index.html.twig', [
                    'controller_name' => 'RepasnewController',
                    'familles' => $familles,
                    'famillesPresentes' => $famillesPresentes,
                    'amis' => $amis,
                    'amisId' => $amisPresentsId,
                    'amisPresents' => $amisPresents,
                ]);
            }
            //récupérer les amis dans un tableau        
            foreach ($amisPresentsId as $amiPresentId) {
                $amiPresent = $amiRepository->find($amiPresentId);
                $amisPresents[] = $amiPresent;
            }
            foreach ($amisPresents as $amiPresent) {
                //récupérer leur famille
                $famillesPresentes[] = $amiPresent->getFamille();
            }
            //dd($famillesPresentes);
            return $this->redirectToRoute('app_repasnewrecette', [
                'amisPresentsId' => $amisPresentsId,
                'famillesPresentes' => $famillesPresentes,
                'dateRepas' => $dateRepas,
            ], Response::HTTP_SEE_OTHER);

            ############  fin du submit en dessous
        }



        return $this->render('repasnew/index.html.twig', [
            'controller_name' => 'RepasnewController',
            'familles' => $familles,
            'famillesPresentes' => $famillesPresentes,
            'amis' => $amis,
            'amisId' => $amisPresentsId,
            'amisPresents' => $amisPresents,
        ]);
    }
}
