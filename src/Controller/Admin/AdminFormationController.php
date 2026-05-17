<?php

namespace App\Controller\Admin;

use App\Entity\Formation;
use App\Form\FormationFormType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de gestion des formations (back-office)
 * Jomaa Lilia
 */
#[Route('/admin/formations', name: 'admin_formations')]
class AdminFormationController extends AbstractController
{
    /**
     * Affiche la liste des formations
     */
    #[Route('', name: '')]
    public function index(FormationRepository $repo, CategorieRepository $catRepo): Response
    {
        return $this->render('admin/formations/index.html.twig', [
            'formations' => $repo->findAll(),
            'categories' => $catRepo->findAll(),
        ]);
    }

    /**
     * Affiche la liste des formations triées sur un champ
     */
    #[Route('/tri/{champ}/{ordre}/{table}', name: '_sort', defaults: ['table' => ''])]
    public function sort($champ, $ordre, FormationRepository $repo, CategorieRepository $catRepo, $table=''): Response
    {
        return $this->render('admin/formations/index.html.twig', [
            'formations' => $repo->findAllOrderBy($champ, $ordre, $table),
            'categories' => $catRepo->findAll(),
        ]);
    }

    /**
     * Affiche la liste des formations filtrées sur un champ
     */
    #[Route('/recherche/{champ}/{table}', name: '_search', defaults: ['table' => ''])]
    public function search($champ, Request $request, FormationRepository $repo, CategorieRepository $catRepo, $table=''): Response
    {
        $valeur = $request->get('recherche');
        return $this->render('admin/formations/index.html.twig', [
            'formations' => $repo->findByContainValue($champ, $valeur, $table),
            'categories' => $catRepo->findAll(),
            'valeur' => $valeur,
            'table' => $table,
        ]);
    }

    /**
     * Supprime une formation et ses liens avec les playlists
     */
    #[Route('/supprimer/{id}', name: '_supprimer', methods: ['POST'])]
    public function supprimer(int $id, FormationRepository $repo, EntityManagerInterface $em, Request $request): Response
    {
        $formation = $repo->find($id);
        if ($formation && $this->isCsrfTokenValid('supprimer_formation_'.$id, $request->request->get('_token'))) {
            $em->remove($formation);
            $em->flush();
        }
        return $this->redirectToRoute('admin_formations');
    }

    /**
     * Affiche le formulaire d'ajout d'une formation et traite la soumission
     */
    #[Route('/ajouter', name: '_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em, CategorieRepository $catRepo): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($formation);
            $em->flush();
            return $this->redirectToRoute('admin_formations');
        }
        return $this->render('admin/formations/form.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Ajouter une formation',
        ]);
    }

    /**
     * Affiche le formulaire de modification d'une formation et traite la soumission
     */
    #[Route('/modifier/{id}', name: '_modifier')]
    public function modifier(int $id, Request $request, FormationRepository $repo, EntityManagerInterface $em): Response
    {
        $formation = $repo->find($id);
        if (!$formation) {
            return $this->redirectToRoute('admin_formations');
        }
        $form = $this->createForm(FormationFormType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_formations');
        }
        return $this->render('admin/formations/form.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Modifier une formation',
        ]);
    }
}