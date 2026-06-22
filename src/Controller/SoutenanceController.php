<?php
namespace App\Controller;

use App\Entity\Soutenance;
use App\Form\SoutenanceType;
use App\Repository\SoutenanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\EnseignantRepository;

#[Route('/admin/soutenances')]

class SoutenanceController extends AbstractController
{
    #[Route('/', name: 'app_soutenance_index')]
    public function index(Request $request, SoutenanceRepository $repo): Response
    {
        $date = $request->query->get('date');
        $soutenances = $date
            ? $repo->findByDate(new \DateTime($date))
            : $repo->findAll();

        return $this->render('soutenance/index.html.twig', [
            'soutenances' => $soutenances,
            'date'        => $date,
        ]);
    }

    #[Route('/new', name: 'app_soutenance_new')]
    public function new(Request $request, EntityManagerInterface $em, SoutenanceRepository $repo): Response
    {
        $soutenance = new Soutenance();
        $form = $this->createForm(SoutenanceType::class, $soutenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conflit = $repo->findConflict(
                $soutenance->getSalle(),
                $soutenance->getDate(),
                $soutenance->getHeure()
            );
           
            if ($conflit) {
                $this->addFlash('danger', 'Cette salle est déjà occupée à cette heure.');
                return $this->render('soutenance/form.html.twig', ['form' => $form, 'title' => 'Programmer une soutenance']);
            }

            $conflitJury = $repo->findJuryConflict(
                [$soutenance->getPresident(), $soutenance->getRapporteur(), $soutenance->getExaminateur()],
                $soutenance->getDate(),
                $soutenance->getHeure()
            );
            if ($conflitJury) {
                $this->addFlash('danger', 'Un membre du jury est déjà occupé à cette heure.');
                return $this->render('soutenance/form.html.twig', ['form' => $form, 'title' => 'Programmer une soutenance']);
            }

            $em->persist($soutenance);
            $em->flush();
            $this->addFlash('success', 'Soutenance programmée avec succès.');
            return $this->redirectToRoute('app_soutenance_index');
        }

        return $this->render('soutenance/form.html.twig', [
            'form'  => $form,
            'title' => 'Programmer une soutenance',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_soutenance_edit')]
    public function edit(Soutenance $soutenance, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SoutenanceType::class, $soutenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Soutenance modifiée.');
            return $this->redirectToRoute('app_soutenance_index');
        }

        return $this->render('soutenance/form.html.twig', [
            'form'  => $form,
            'title' => 'Modifier une soutenance',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_soutenance_delete', methods: ['POST'])]
    public function delete(Soutenance $soutenance, EntityManagerInterface $em): Response
    {
        $em->remove($soutenance);
        $em->flush();
        $this->addFlash('success', 'Soutenance annulée.');
        return $this->redirectToRoute('app_soutenance_index');
    }
    
}