<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ResourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(CategoryRepository $categoryRepository,ResourceRepository $resourceRepository): Response
    {

        return $this->render('default/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'resources'   => $resourceRepository->resourceLimitHome()
        ]);
    }
}
