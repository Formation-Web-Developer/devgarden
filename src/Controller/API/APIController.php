<?php


namespace App\Controller\API;



use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api", name="api_")
 */
class APIController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/categories/{search}", name="categories", priority=3, methods={"GET"})
     */
    public function categories(CategoryRepository $categoryRepository, string $search = ''): JsonResponse
    {
        return $this->json($this->serializer->serialize($categoryRepository->search($search), 'json', [
            'groups' => ['category']
        ]));
    }

}
