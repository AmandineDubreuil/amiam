<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Form\SearchType;
use App\Service\PictureService;
use App\Entity\RecetteIngredient;
use App\Entity\User;
use App\Form\RecetteNewBeginType;
use App\Service\PdfGeneratorService;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RecetteIngredientRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


#[Route('/recettes')]
#[IsGranted('ROLE_USER')]

class RecetteController extends AbstractController
{
    private $security;
    private $entityManager;
    private $params;


    public function __construct(Security $security, EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->params = $params;
    }

    #[Route('/', name: 'app_recette_index', methods: ['GET', 'POST'])]
    public function index(
        User $user,
        RecetteRepository $recetteRepository,
        Request $request
    ): Response {
        $user = $this->security->getUser();

        $recettes = $recetteRepository->findByUser($user);
        // formulaire recherche
      
        $form = $this->createForm(SearchType::class);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $searchArray = $form->getData();
            $searchData = $searchArray['search'];
            $recettes = $recetteRepository->findBySearch($searchData);
           
            return $this->render('recette/index.html.twig', [
                'form' => $form,
                'recettes' => $recettes,
                'user' => $user,
            ]);
        }

        return $this->render('recette/index.html.twig', [
            'form' => $form->createView(),
            'recettes' => $recetteRepository->findAll(),
            'user' => $user,
        ]);
    }

    #[Route('/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();

        $recette = new Recette();
        $form = $this->createForm(RecetteNewBeginType::class, $recette);
        $form->handleRequest($request);

        //validation du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setUser($user);

            $recette->setCreatedAt(new \DateTimeImmutable);
            $recette->setModifiedAt(new \DateTimeImmutable);

            $entityManager->persist($recette);
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_edit', [
                'id' => $recette,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'recetteNewBeginForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_show', methods: ['GET'])]
    public function show(Recette $recette): Response
    {
        if ($recette->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $ingredients = $recette->getIngredients();
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
        ]);
    }
    #[Route('/pdf/{id}', name: 'app_recette_pdf', methods: ['GET'])]
    public function outputPdf(
        Recette $recette,
        PdfGeneratorService $pdfGeneratorService
    ): Response {

        $ingredients = $recette->getIngredients();
        $html = $this->render('recette/pdf.html.twig', [
            'recette' => $recette,
            'ingredients' => $ingredients,
        ]);
        $content = $pdfGeneratorService->getPdf($html);
        $titre = $recette->getTitre();
        $titrePdf = "\"" . $titre . ".pdf\"";

        $contentDisposition = "'attachment; filename=" . $titrePdf;
        // dd($contentDisposition);

        return new Response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => $contentDisposition
        ]);
    }
    #[Route('/clone/{id}', name: 'app_recette_clone', methods: ['GET', 'POST'])]
    public function clone(
        Recette $recette,
        PictureService $pictureService,
        RecetteIngredient $recetteIngredient,
        RecetteRepository $recetteRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        $user = $this->security->getUser();
        // récupération de la recette
        $oldRecetteId = $recette->getId();

        // Coller recette
        $newRecette = new Recette();

        $newRecette->setUser($user);
        $newRecette->setPrive($oldRecetteId);
        $newRecette->setCreatedAt(new \DateTimeImmutable);
        $newRecette->setModifiedAt(new \DateTimeImmutable);

        $newRecette->setTitre($recette->getTitre($oldRecetteId));
        $newRecette->setNbPersonnes($recette->getNbPersonnes($oldRecetteId));
        $newRecette->setTpsPreparation($recette->getTpsPreparation($oldRecetteId));
        $newRecette->setTpsCuisson($recette->getTpsCuisson($oldRecetteId));
        $newRecette->setTpsRepos($recette->getTpsRepos($oldRecetteId));
        $newRecette->setDescription($recette->getDescription($oldRecetteId));
        $newRecette->setVideo($recette->getVideo($oldRecetteId));
        $newRecette->setPrive($recette->isPrive($oldRecetteId));
        $newRecette->setCategorie($recette->getCategorie($oldRecetteId));

        //gestion de la photo
        $oldImageName = $recette->getPhoto($oldRecetteId);
        $oldImage = 'assets/uploads/photosRecettes/mini/' . $oldImageName;

        $newImageName = '300x300-' . md5(uniqid(rand(), true)) . '.webp';

        $destinationNewImage = 'assets/uploads/photosRecettes/mini/' . $newImageName;

        copy($oldImage, $destinationNewImage);


        $newRecette->setPhoto($newImageName);

        $entityManager->persist($newRecette);
        $entityManager->flush();


        // ingrédients de la recette

        foreach ($recette->getIngredients() as $ingredient) {

            //récupération de l'ancien ingrédient
            $oldRecetteIngredientId  = $ingredient->getId();

            //coller l'ingrédient
            $newRecetteIngredient = new RecetteIngredient();

            $newRecetteIngredient->setRecette($newRecette);
            $newRecetteIngredient->setAliment($ingredient->getAliment($oldRecetteIngredientId));
            $newRecetteIngredient->setQuantite($ingredient->getQuantite($oldRecetteIngredientId));
            $newRecetteIngredient->setMesure($ingredient->getMesure($oldRecetteIngredientId));

            $entityManager->persist($newRecetteIngredient);
            $entityManager->flush();
        }


        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);

        return $this->render('recette/show.html.twig', [
            'recette' => $newRecette,
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Recette $recette,
        EntityManagerInterface $entityManager,
        PictureService $pictureService
    ): Response {

        if ($recette->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $idRecette = $recette->getId();
        $ingredients = $recette->getIngredients();
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recette->setModifiedAt(new \DateTimeImmutable);


            // modification / ajout de la photo
            // on récupère l'image
            $image = $form->get('photo')->getData();
            // on définit le dossier de destination
            $folder = 'photosRecettes';
            if ($image) {
                // on supprime l'ancienne image
                $oldImage = $recette->getPhoto();
                if ($oldImage) {
                    $pictureService->delete($oldImage, $folder);
                }
                //on appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);
                $recette->setPhoto($fichier);
            }

            // *****
            $entityManager->flush();

            return $this->redirectToRoute('app_recette_show', [
                'id' => $idRecette,
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'recetteForm' => $form,
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Recette $recette,
        RecetteIngredientRepository $recetteIngredientRepository,
        PictureService $pictureService,
        EntityManagerInterface $entityManager,
    ): Response {

        if ($recette->getUser() != $this->getUser()) {
            return  $this->redirectToRoute('app_page404', [], Response::HTTP_SEE_OTHER);
        }

        $recetteId = $recette->getId();
        $recetteIngredients = $recetteIngredientRepository->findByRecette($recetteId);
        $repas = $recette->getRepas();



        if ($this->isCsrfTokenValid('delete' . $recette->getId(), $request->request->get('_token'))) {

            //suppression de l'image associée à la recette
            $oldImage = $recette->getPhoto();
            if ($oldImage) {
                $folder = 'photosRecettes';
                $pictureService->delete($oldImage, $folder);
            }

            //suppression des ingrédients associés à la recette
            foreach ($recetteIngredients as $recetteIngredient) {
                $entityManager->remove($recetteIngredient);
            }

            // suppression de la recette dans les repas associés
            foreach ($repas as $repa) {

                $repaRecettes = $repa->getRecettes();
                foreach ($repaRecettes as $repaRecette) {
                    if ($repaRecette === $recette) {
                        $entityManager->remove($recette);
                    }
                }
            }

            $entityManager->remove($recette);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recette_index', [], Response::HTTP_SEE_OTHER);
    }
}
