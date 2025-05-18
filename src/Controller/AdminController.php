<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recette;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    #[Route('/users', name: 'app_admin_users')]
    public function users(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/{id}/toggle-role/{role}', name: 'app_admin_toggle_role', methods: ['POST'])]
    public function toggleRole(Request $request, User $user, string $role, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('toggle_role'.$user->getId(), $request->request->get('_token'))) {
            $roles = $user->getRoles();
            
            if ($role === 'ROLE_BANNED' && !in_array($role, $roles)) {
                $roles = array_diff($roles, ['ROLE_USER']);
                $roles[] = 'ROLE_BANNED';
            }
            elseif ($role === 'ROLE_BANNED' && in_array($role, $roles)) {
                $roles = array_diff($roles, ['ROLE_BANNED']);
                $roles[] = 'ROLE_USER';
            }
            else {
                if (in_array($role, $roles)) {
                    $roles = array_diff($roles, [$role]);
                } else {
                    $roles[] = $role;
                }
            }
            
            $user->setRoles($roles);
            $entityManager->flush();
            
            $message = $role === 'ROLE_BANNED' 
                ? (in_array('ROLE_BANNED', $roles) ? 'L\'utilisateur a été banni.' : 'L\'utilisateur a été débanni.')
                : 'Les rôles de l\'utilisateur ont été mis à jour.';
            
            $this->addFlash('success', $message);
        }

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/users/{id}/delete', name: 'app_admin_user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_user'.$user->getId(), $request->request->get('_token'))) {
            foreach ($user->getRecettes() as $recette) {
                foreach ($recette->getReviews() as $review) {
                    $entityManager->remove($review);
                }
                $entityManager->remove($recette);
            }

            foreach ($user->getReviews() as $review) {
                $entityManager->remove($review);
            }

            $entityManager->remove($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'utilisateur a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_users');
    }

    #[Route('/recettes', name: 'app_admin_recettes')]
    public function recettes(Request $request, EntityManagerInterface $entityManager): Response
    {
        $authorId = $request->query->get('author');
        $qb = $entityManager->getRepository(Recette::class)->createQueryBuilder('r');
        
        if ($authorId) {
            $qb->andWhere('r.author = :author')
               ->setParameter('author', $authorId);
        }
        
        $recettes = $qb->getQuery()->getResult();

        return $this->render('admin/recettes.html.twig', [
            'recettes' => $recettes,
            'authorId' => $authorId,
        ]);
    }

    #[Route('/recettes/{id}/delete', name: 'app_admin_recette_delete', methods: ['POST'])]
    public function deleteRecette(Request $request, Recette $recette, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_recette'.$recette->getId(), $request->request->get('_token'))) {
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

        return $this->redirectToRoute('app_admin_recettes');
    }

    #[Route('/reviews', name: 'app_admin_reviews')]
    public function reviews(Request $request, EntityManagerInterface $entityManager): Response
    {
        $authorId = $request->query->get('author');
        $qb = $entityManager->getRepository(Review::class)->createQueryBuilder('r');
        
        if ($authorId) {
            $qb->andWhere('r.author = :author')
               ->setParameter('author', $authorId);
        }
        
        $reviews = $qb->getQuery()->getResult();

        return $this->render('admin/reviews.html.twig', [
            'reviews' => $reviews,
            'authorId' => $authorId,
        ]);
    }

    #[Route('/reviews/{id}/delete', name: 'app_admin_review_delete', methods: ['POST'])]
    public function deleteReview(Request $request, Review $review, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_review'.$review->getId(), $request->request->get('_token'))) {
            $entityManager->remove($review);
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'avis a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_reviews');
    }
}
