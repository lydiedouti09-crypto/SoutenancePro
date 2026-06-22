<?php
namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\EtudiantType;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\SoutenanceRepository;

#[Route('/admin/etudiants')]
#[IsGranted('ROLE_ADMIN')]
class EtudiantController extends AbstractController
{
    #[Route('/', name: 'app_etudiant_index')]
    public function index(Request $request, EtudiantRepository $repo): Response
    {
        $search = $request->query->get('search', '');
        $etudiants = $search
            ? $repo->findByNom($search)
            : $repo->findAll();

        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $etudiants,
            'search'    => $search,
        ]);
    }

    #[Route('/new', name: 'app_etudiant_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($etudiant);
            $em->flush();
            $this->addFlash('success', 'Étudiant ajouté avec succès.');
            return $this->redirectToRoute('app_etudiant_index');
        }

        return $this->render('etudiant/form.html.twig', [
            'form'  => $form,
            'title' => 'Ajouter un étudiant',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etudiant_edit')]
    public function edit(Etudiant $etudiant, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Étudiant modifié avec succès.');
            return $this->redirectToRoute('app_etudiant_index');
        }

        return $this->render('etudiant/form.html.twig', [
            'form'  => $form,
            'title' => 'Modifier un étudiant',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_etudiant_delete', methods: ['POST'])]
public function delete(Etudiant $etudiant, EntityManagerInterface $em, SoutenanceRepository $soutenanceRepo): Response
{
    $soutenance = $soutenanceRepo->findOneBy(['etudiant' => $etudiant]);

    if ($soutenance) {
        $this->addFlash('danger', 'Impossible de supprimer cet étudiant : il a une soutenance programmée. Annulez d\'abord la soutenance.');
        return $this->redirectToRoute('app_etudiant_index');
    }

    $em->remove($etudiant);
    $em->flush();
    $this->addFlash('success', 'Étudiant supprimé.');
    return $this->redirectToRoute('app_etudiant_index');
}
}