<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Recette;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/review')]
final class ReviewController extends AbstractController
{
    #[Route('/new/{recette}', name: 'app_review_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($recette->getAuthor() === $user) {
            $this->addFlash('error', 'Vous ne pouvez pas commenter votre propre recette.');
            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()]);
        }

        $review = new Review();
        $review->setAuthor($user);
        $review->setRecette($recette);
        $review->setCreateAt(new \DateTime());
        
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash('success', 'Votre avis a été publié avec succès !');
            return $this->redirectToRoute('app_recette_show', ['id' => $recette->getId()]);
        }

        return $this->render('review/new.html.twig', [
            'review' => $review,
            'recette' => $recette,
            'form' => $form,
        ]);
    }
}
