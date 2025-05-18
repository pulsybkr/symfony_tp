<?php

namespace App\Controller;

use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class IngredientController extends AbstractController
{
    #[Route('/ingredients/search', name: 'app_ingredient_search', methods: ['GET'])]
    public function search(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $query = $request->query->get('q', '');
        
        if (strlen($query) < 2) {
            return new JsonResponse([]);
        }

        $ingredients = $entityManager->getRepository(Ingredient::class)
            ->createQueryBuilder('i')
            ->select('DISTINCT i.name')
            ->where('LOWER(i.name) LIKE LOWER(:query)')
            ->setParameter('query', '%' . $query . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $results = array_map(function($item) {
            return ['name' => $item['name']];
        }, $ingredients);

        return new JsonResponse($results);
    }
} 