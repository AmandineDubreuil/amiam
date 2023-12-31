<?php

namespace App\Controller\Admin;

use App\Entity\Aliment;
use App\Entity\Allergene;
use App\Entity\GroupeAli;
use App\Entity\Mesure;
use App\Entity\RecetteCategorie;
use App\Entity\Regime;
use App\Entity\Saison;
use App\Entity\SousGroupeAli;
use App\Entity\User;
use App\Entity\UserFamily;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');

            }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration Amiam')
            ->renderContentMaximized();

    }

    public function configureMenuItems(): iterable
    {
               yield MenuItem::linktoRoute('Site Amiam', 'fas fa-home', 'app_home');
              // yield MenuItem::linkToDashboard('Administration Amiam', 'fa fa-home');
               yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-people-group', User::class);
               yield MenuItem::linkToCrud('Membres de la famille d\'un utilisateur', 'fa-solid fa-people-group', UserFamily::class);
               yield MenuItem::linkToCrud('Aliments', 'fas fa-list', Aliment::class);

               yield MenuItem::linkToCrud('Allergenes', 'fas fa-list', Allergene::class);

               yield MenuItem::linkToCrud('Regime', 'fas fa-list', Regime::class);

               yield MenuItem::linkToCrud('Saison', 'fas fa-list', Saison::class);
               yield MenuItem::linkToCrud('Groupe Aliments', 'fas fa-list', GroupeAli::class);
               yield MenuItem::linkToCrud('Sous-Groupe Aliments', 'fas fa-list', SousGroupeAli::class);
               yield MenuItem::linkToCrud('Catégories Recettes', 'fas fa-list', RecetteCategorie::class);
               yield MenuItem::linkToCrud('Unités de mesure Ingredients', 'fas fa-list', Mesure::class);



        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
