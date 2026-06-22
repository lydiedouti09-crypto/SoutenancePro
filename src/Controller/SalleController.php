<?php
namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\SoutenanceRepository;

#[Route('/admin/salles')]
#[IsGranted('ROLE_ADMIN')]
class SalleController extends AbstractController
{
    #[Route('/', name: 'app_salle_index')]
    public function index(SalleRepository $repo): Response
    {
        return $this->render('salle/index.html.twig', [
            'salles' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_salle_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($salle);
            $em->flush();
            $this->addFlash('success', 'Salle ajoutée avec succès.');
            return $this->redirectToRoute('app_salle_index');
        }

        return $this->render('salle/form.html.twig', [
            'form'  => $form,
            'title' => 'Ajouter une salle',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salle_edit')]
    public function edit(Salle $salle, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Salle modifiée avec succès.');
            return $this->redirectToRoute('app_salle_index');
        }

        return $this->render('salle/form.html.twig', [
            'form'  => $form,
            'title' => 'Modifier une salle',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_salle_delete', methods: ['POST'])]
public function delete(Salle $salle, EntityManagerInterface $em, SoutenanceRepository $soutenanceRepo): Response
{
    $soutenance = $soutenanceRepo->findOneBy(['salle' => $salle]);

    if ($soutenance) {
        $this->addFlash('danger', 'Impossible de supprimer cette salle : elle est affectée à une soutenance programmée.');
        return $this->redirectToRoute('app_salle_index');
    }

    $em->remove($salle);
    $em->flush();
    $this->addFlash('success', 'Salle supprimée.');
    return $this->redirectToRoute('app_salle_index');
}
}