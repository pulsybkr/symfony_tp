<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();
        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/edit', name: 'app_user_profile_edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            return $this->redirectToRoute('app_user_profile');
        }

        return $this->render('user/edit_profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/recettes', name: 'app_user_recettes')]
    public function recettes(): Response
    {
        $user = $this->getUser();
        return $this->render('user/recipes.html.twig', [
            'user' => $user,
            'recipes' => $user->getRecettes()
        ]);
    }

    #[Route('/favorites', name: 'app_user_favorites')]
    public function favorites(): Response
    {
        $user = $this->getUser();
        return $this->render('user/favorites.html.twig', [
            'user' => $user,
            'favorites' => $user->getFavorites()
        ]);
    }
}
