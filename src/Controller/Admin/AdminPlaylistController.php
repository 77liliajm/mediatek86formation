<?php

namespace App\Controller\Admin;

use App\Entity\Playlist;
use App\Form\PlaylistFormType;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur de gestion des playlists (back-office)
 * Jomaa Lilia
 */
#[Route('/admin/playlists', name: 'admin_playlists')]
class AdminPlaylistController extends AbstractController
{
    /**
     * Affiche la liste des playlists
     */
    #[Route('', name: '')]
    public function index(PlaylistRepository $repo): Response
    {
        return $this->render('admin/playlists/index.html.twig', [
            'playlists' => $repo->findAll(),
        ]);
    }

    /**
     * Affiche le formulaire d'ajout d'une playlist et traite la soumission
     */
    #[Route('/ajouter', name: '_ajouter')]
    public function ajouter(Request $request, EntityManagerInterface $em): Response
    {
        $playlist = new Playlist();
        $form = $this->createForm(PlaylistFormType::class, $playlist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($playlist);
            $em->flush();
            return $this->redirectToRoute('admin_playlists');
        }
        return $this->render('admin/playlists/form.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Ajouter une playlist',
        ]);
    }

    /**
     * Affiche le formulaire de modification d'une playlist et traite la soumission
     */
    #[Route('/modifier/{id}', name: '_modifier')]
    public function modifier(int $id, Request $request, PlaylistRepository $repo, EntityManagerInterface $em): Response
    {
        $playlist = $repo->find($id);
        if (!$playlist) {
            return $this->redirectToRoute('admin_playlists');
        }
        $form = $this->createForm(PlaylistFormType::class, $playlist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('admin_playlists');
        }
        return $this->render('admin/playlists/form.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Modifier une playlist',
            'playlist' => $playlist,
        ]);
    }

    /**
     * Supprime une playlist si aucune formation n'y est rattachée
     */
    #[Route('/supprimer/{id}', name: '_supprimer', methods: ['POST'])]
    public function supprimer(int $id, PlaylistRepository $repo, EntityManagerInterface $em, Request $request): Response
    {
        $playlist = $repo->find($id);
        if ($playlist && $this->isCsrfTokenValid('supprimer_playlist_'.$id, $request->request->get('_token'))) {
            if ($playlist->getFormations()->isEmpty()) {
                $em->remove($playlist);
                $em->flush();
            }
        }
        return $this->redirectToRoute('admin_playlists');
    }
}