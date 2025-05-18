<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recette')]
final class RecetteController extends AbstractController
{
    #[Route('/', name: 'app_recette')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $recettes = $entityManager->getRepository(Recette::class)->findAll();

        return $this->render('recette/index.html.twig', [
            'controller_name' => 'RecetteController',
            'recettes' => $recettes,
        ]);
    }

    #[Route('/new', name: 'app_recette_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        
        if (in_array('ROLE_BANNED', $user->getRoles())) {
            $this->addFlash('error', 'Votre compte a été banni. Vous ne pouvez pas créer de recettes.');
            return $this->redirectToRoute('app_recette');
        }

        $recette = new Recette();
        $recette->setAuthor($user);
        $recette->setCreateAt(new \DateTime());
        
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($recette->getIngredients() as $ingredient) {
                $ingredient->setRecette($recette);
                $entityManager->persist($ingredient);
            }

            $position = 1;
            foreach ($recette->getSteps() as $step) {
                $step->setRecette($recette);
                $step->setPosition($position++);
                $entityManager->persist($step);
            }

            $entityManager->persist($recette);
            $entityManager->flush();

            $this->addFlash('success', 'Votre recette a été créée avec succès !');
            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()]);
        }

        return $this->render('recette/new.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recette_show', requirements: ['id' => '\d+'])]
    public function show(Recette $recette): Response
    {
        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recette_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($recette->getAuthor() !== $this->getUser()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à modifier cette recette.');
            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()]);
        }

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($recette->getIngredients() as $ingredient) {
                $ingredient->setRecette($recette);
                $entityManager->persist($ingredient);
            }

            $position = 1;
            foreach ($recette->getSteps() as $step) {
                $step->setRecette($recette);
                $step->setPosition($position++);
                $entityManager->persist($step);
            }

            $recette->setUpdateAt(new \DateTime());
            $entityManager->flush();

            $this->addFlash('success', 'Votre recette a été modifiée avec succès !');
            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()]);
        }

        return $this->render('recette/edit.html.twig', [
            'recette' => $recette,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_recette_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($recette->getAuthor() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cette recette.');
        }

        if ($this->isCsrfTokenValid('delete'.$recette->getId(), $request->request->get('_token'))) {
            foreach ($recette->getReviews() as $review) {
                $entityManager->remove($review);
            }
            
            foreach ($recette->getIngredients() as $ingredient) {
                $entityManager->remove($ingredient);
            }
            
            foreach ($recette->getSteps() as $step) {
                $entityManager->remove($step);
            }
            
            $entityManager->remove($recette);
            $entityManager->flush();
            
            $this->addFlash('success', 'La recette a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_recette');
    }
}
