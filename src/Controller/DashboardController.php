<?php
namespace App\Controller;

use App\Repository\EtudiantRepository;
use App\Repository\EnseignantRepository;
use App\Repository\SalleRepository;
use App\Repository\SoutenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(
        EtudiantRepository $etudiantRepo,
        EnseignantRepository $enseignantRepo,
        SalleRepository $salleRepo,
        SoutenanceRepository $soutenanceRepo
    ): Response {
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        if ($isAdmin) {
            return $this->render('dashboard/index.html.twig', [
                'nbEtudiants'    => $etudiantRepo->count([]),
                'nbEnseignants'  => $enseignantRepo->count([]),
                'nbSalles'       => $salleRepo->count([]),
                'nbSoutenances'  => $soutenanceRepo->count([]),
            ]);
        }

        // dashboard enseignant
        $enseignant = $enseignantRepo->findOneBy(['email' => $user->getEmail()]);
        $soutenances = $soutenanceRepo->findByEnseignant($enseignant);

        return $this->render('dashboard/enseignant.html.twig', [
            'enseignant'    => $enseignant,
            'soutenances'   => $soutenances,
            'nbSoutenances' => count($soutenances),
        ]);
    }
}