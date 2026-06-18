<?php
namespace App\Controller;

use App\Repository\EnseignantRepository;
use App\Repository\SoutenanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class EnseignantSoutenanceController extends AbstractController
{
    #[Route('/enseignant/soutenances', name: 'app_enseignant_soutenances')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function mesSoutenances(SoutenanceRepository $repo, EnseignantRepository $enseignantRepo): Response
    {
        $enseignant = $enseignantRepo->findOneBy(['email' => $this->getUser()->getEmail()]);
        $soutenances = $repo->findByEnseignant($enseignant);

        return $this->render('soutenance/enseignant_index.html.twig', [
            'soutenances' => $soutenances,
            'enseignant'  => $enseignant,
        ]);
    }
    #[Route('/enseignant/etudiants', name: 'app_enseignant_etudiants')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
public function mesEtudiants(SoutenanceRepository $repo, EnseignantRepository $enseignantRepo): Response
{
    $enseignant = $enseignantRepo->findOneBy(['email' => $this->getUser()->getEmail()]);
    $soutenances = $repo->findByEnseignant($enseignant);

    $etudiants = array_map(fn($s) => $s->getEtudiant(), $soutenances);

    return $this->render('soutenance/enseignant_etudiants.html.twig', [
        'etudiants' => $etudiants,
    ]);
}
}