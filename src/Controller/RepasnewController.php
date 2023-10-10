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
        $regimes = "";
        $allergiesGroupe = "";
        $allergiesAliment = "";
        $degouts = "";
        $regimeSsPorc = 0;
        $recettePorc = 0;
        $recetteAEviter = 0;

        // partie récap recettes choisies

        $recettesChoisies = [];

        if ($request->isMethod('POST') && $request->request->has('submit')) {

            ########## DEBUT PARTIE AMIS #########

            // Récupérez les amis sélectionnés
            $amisPresentsId = $request->request->all('amisPourRecettes');
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
