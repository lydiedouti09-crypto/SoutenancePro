<?php
namespace App\Controller;

use App\Entity\Enseignant;
use App\Form\EnseignantType;
use App\Repository\EnseignantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\SoutenanceRepository;

#[Route('/admin/enseignants')]
#[IsGranted('ROLE_ADMIN')]
class EnseignantController extends AbstractController
{
    #[Route('/', name: 'app_enseignant_index')]
    public function index(EnseignantRepository $repo): Response
    {
        return $this->render('enseignant/index.html.twig', [
            'enseignants' => $repo->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_enseignant_new')]
public function new(Request $request, EntityManagerInterface $em): Response
{
    $enseignant = new Enseignant();
    $form = $this->createForm(EnseignantType::class, $enseignant);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photoFile')->getData();
        if ($photoFile) {
            $filename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move($this->getParameter('photos_directory'), $filename);
            $enseignant->setPhoto($filename);
        }
        $em->persist($enseignant);
        $em->flush();
        $this->addFlash('success', 'Enseignant ajouté avec succès.');
        return $this->redirectToRoute('app_enseignant_index');
    }

    return $this->render('enseignant/form.html.twig', [
        'form'  => $form,
        'title' => 'Ajouter un enseignant',
    ]);
}

#[Route('/{id}/edit', name: 'app_enseignant_edit')]
public function edit(Enseignant $enseignant, Request $request, EntityManagerInterface $em): Response
{
    $form = $this->createForm(EnseignantType::class, $enseignant);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photoFile = $form->get('photoFile')->getData();
        if ($photoFile) {
            $filename = uniqid().'.'.$photoFile->guessExtension();
            $photoFile->move($this->getParameter('photos_directory'), $filename);
            $enseignant->setPhoto($filename);
        }
        $em->flush();
        $this->addFlash('success', 'Enseignant modifié avec succès.');
        return $this->redirectToRoute('app_enseignant_index');
    }

    return $this->render('enseignant/form.html.twig', [
        'form'  => $form,
        'title' => 'Modifier un enseignant',
    ]);
}

#[Route('/{id}/delete', name: 'app_enseignant_delete', methods: ['POST'])]
public function delete(Enseignant $enseignant, EntityManagerInterface $em, SoutenanceRepository $soutenanceRepo): Response
{
    $soutenances = $soutenanceRepo->findByEnseignant($enseignant);

    if (count($soutenances) > 0) {
        $this->addFlash('danger', 'Impossible de supprimer cet enseignant : il est affecté à '.count($soutenances).' soutenance(s). Annulez ou réaffectez ces soutenances avant de le supprimer.');
        return $this->redirectToRoute('app_enseignant_index');
    }

    $em->remove($enseignant);
    $em->flush();
    $this->addFlash('success', 'Enseignant supprimé.');
    return $this->redirectToRoute('app_enseignant_index');
}
}