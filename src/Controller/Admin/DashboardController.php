<?php

namespace App\Controller\Admin;

use App\Entity\Allergene;
use App\Entity\Regime;
use App\Entity\User;
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
               yield MenuItem::linkToDashboard('Administration Amiam', 'fa fa-home');
               yield MenuItem::linkToCrud('Utilisateurs', 'fa-solid fa-people-group', User::class);
               yield MenuItem::linkToCrud('Allergenes', 'fas fa-list', Allergene::class);
               yield MenuItem::linkToCrud('Regime', 'fas fa-list', Regime::class);

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
