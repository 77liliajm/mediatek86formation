<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de gestion des catégories (back-office)
 Jomaa Lilia
 */
#[Route('/admin/categories', name: 'admin_categories')]
class AdminCategorieController extends AbstractController
{
    /**
     * Affiche la liste des catégories
     */
    #[Route('', name: '')]
    public function index(CategorieRepository $repo): Response
    {
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $repo->findAll(),
            'erreur' => null,
        ]);
    }

    /**
     * Ajoute une nouvelle catégorie avec vérification d'unicité du nom
     */
    #[Route('/ajouter', name: '_ajouter', methods: ['POST'])]
    public function ajouter(Request $request, CategorieRepository $repo, EntityManagerInterface $em): Response
    {
        $nom = trim($request->request->get('nom'));
        $erreur = null;

        if (empty($nom)) {
            $erreur = 'Le nom est obligatoire.';
        } else {
            $existante = $repo->findOneBy(['name' => $nom]);
            if ($existante) {
                $erreur = 'Cette catégorie existe déjà.';
            }
        }

        if ($erreur) {
            return $this->render('admin/categories/index.html.twig', [
                'categories' => $repo->findAll(),
                'erreur' => $erreur,
            ]);
        }

        $categorie = new Categorie();
        $categorie->setName($nom);
        $em->persist($categorie);
        $em->flush();

        return $this->redirectToRoute('admin_categories');
    }

    /**
     * Supprime une catégorie si elle n'est rattachée à aucune formation
     */
    #[Route('/supprimer/{id}', name: '_supprimer', methods: ['POST'])]
    public function supprimer(int $id, CategorieRepository $repo, EntityManagerInterface $em, Request $request): Response
    {
        $categorie = $repo->find($id);
        if ($categorie && $this->isCsrfTokenValid('supprimer_categorie_'.$id, $request->request->get('_token'))) {
            if ($categorie->getFormations()->isEmpty()) {
                $em->remove($categorie);
                $em->flush();
            }
        }
        return $this->redirectToRoute('admin_categories');
    }
}
/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

