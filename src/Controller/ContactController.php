<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class ContactController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        User $user,
        SendMailService $sendMailService,
    ): Response {
        $user = $this->security->getUser();
      
      //  dd($user->getPseudo());
        if ($request->isMethod('POST') && $request->request->has('submit')) {
         
           if ($user) {
                $pseudo = $user->getPseudo();
                $email = $user->getEmail();

            } else {
                
                
                $pseudo = $request->request->get("pseudo");
                $email = $request->request->get("email");
       
            }
            
        //    dd($user); 
            $objet = $request->request->get("objet");
            $commentaire = $request->request->get("commentaire");
            $objetMail = 'Contact Amiam.fr : ' . $objet;

            $sendMailService->send(
                $email,
                'amandine.dubreuil@hotmail.com',
                $objetMail,
                'contact',
                compact('commentaire', 'pseudo')

            );
            $this->addFlash('success', 'Ton message a bien été envoyé.');

            return $this->redirectToRoute('app_home');

        }

        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            
        ]);
    }
}
