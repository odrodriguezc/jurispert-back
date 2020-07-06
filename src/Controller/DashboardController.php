<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin/dashboard")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard_index")
     */
    public function index(Security $security, ProjectRepository $projectRepository)
    {
        $currentUser = $security->getUser();

        return $this->render('dashboard/index.html.twig', [
            'user' => $currentUser,
        ]);
    }
}
